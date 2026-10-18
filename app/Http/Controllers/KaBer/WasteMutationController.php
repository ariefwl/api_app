<?php

namespace App\Http\Controllers\KaBer;

use App\Http\Controllers\Controller;
use App\Models\KaBer\WasteMutation;
use Illuminate\Http\Request;

class WasteMutationController extends Controller
{
    public function getWasteMutationData(Request $request)
    {
        $columns = [
            0 => 'WasteMutationKB.PartNo',
            1 => 'WasteMutationKB.InvName',
            2 => 'WasteMutationKB.Begin',
            3 => 'WasteMutationKB.In',
            4 => 'WasteMutationKB.Out',
            5 => 'WasteMutationKB.Unit',
            6 => 'WasteMutationKB.Adjustment',
            7 => 'WasteMutationKB.Ending',
            8 => 'WasteMutationKB.StockTaking',
            9 => 'WasteMutationKB.Variance'
        ];

        $length = (int) $request->input('length', 10);
        $start = (int) $request->input('start', 0);
        $search = $request->input('search.value');
        $period = $request->input('period');

        $query = WasteMutation::query()
            ->where('WasteMutationKB.Period', $period)
            ->select([
                'WasteMutationKB.PartNo',
                'WasteMutationKB.InvName',
                'WasteMutationKB.Begin',
                'WasteMutationKB.In',
                'WasteMutationKB.Out',
                'WasteMutationKB.Unit',
                'WasteMutationKB.Adjustment',
                'WasteMutationKB.Ending',
                'WasteMutationKB.StockTaking',
                'WasteMutationKB.Variance'
            ]);

        $totalData = (clone $query)->count();

        $totalFiltered = (clone $query)->count();

        $data = $query
            ->orderBy('WasteMutationKB.PartNo', 'asc')
            ->offset($start)
            ->limit($length)
            ->get();

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }
}
