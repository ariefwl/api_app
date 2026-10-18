<?php

namespace App\Http\Controllers\KaBer;

use App\Http\Controllers\Controller;
use App\Models\KaBer\FGMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FGMutationController extends Controller
{
    public function getFGMutationData(Request $request)
    {
        $columns = [
            0 => 'Inventory.PartNo',
            1 => 'Inventory.InvName',
            2 => 'FGMutationKB.Begin',
            3 => 'FGMutationKB.In',
            4 => 'FGMutationKB.Out',
            5 => 'FGMutationKB.Unit',
            6 => 'FGMutationKB.Adjustment',
            7 => 'FGMutationKB.Ending',
            8 => 'FGMutationKB.StockTaking',
            9 => 'FGMutationKB.Variance'
        ];

        $length = (int) $request->input('length', 10);
        $start = (int) $request->input('start', 0);
        $search = $request->input('search.value');
        $period = $request->input('period');

        $query = FGMutation::query()
            ->leftJoin(
                'JIData.dbo.Inventory as Inventory',
                'FGMutationKB.InvID',
                '=',
                'Inventory.InvId'
            )
            ->where('FGMutationKB.Period', $period)
            ->select([
                DB::raw('Inventory.PartNo'),
                DB::raw('Inventory.InvName'),
                'FGMutationKB.Begin',
                'FGMutationKB.In',
                'FGMutationKB.Out',
                'FGMutationKB.Unit',
                'FGMutationKB.Adjustment',
                'FGMutationKB.Ending',
                'FGMutationKB.StockTaking',
                'FGMutationKB.Variance'
            ]);

        $totalData = (clone $query)->count();

        $totalFiltered = (clone $query)->count();

        $data = $query
            ->orderBy('Inventory.PartNo', 'asc')
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
