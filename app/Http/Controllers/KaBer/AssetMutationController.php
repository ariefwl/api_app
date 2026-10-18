<?php

namespace App\Http\Controllers\KaBer;

use App\Http\Controllers\Controller;
use App\Models\KaBer\AssetMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetMutationController extends Controller
{
    public function getAssetMutationData(Request $request)
    {
        $columns = [
            0 => 'Inventory.PartNo',
            1 => 'Inventory.InvName',
            2 => 'AssetMutationKB.Begin',
            3 => 'AssetMutationKB.In',
            4 => 'AssetMutationKB.Out',
            5 => 'AssetMutationKB.Unit',
            6 => 'AssetMutationKB.Adjustment',
            7 => 'AssetMutationKB.Ending',
            8 => 'AssetMutationKB.StockTaking',
            9 => 'AssetMutationKB.Variance'
        ];

        $length = (int) $request->input('length', 10);
        $start = (int) $request->input('start', 0);
        $search = $request->input('search.value');
        $period = $request->input('period');

        $query = AssetMutation::query()
            ->leftJoin(
                'JIData.dbo.Inventory as Inventory',
                'AssetMutationKB.InvID',
                '=',
                'Inventory.InvId'
            )
            ->where('AssetMutationKB.Period', $period)
            ->select([
                DB::raw('Inventory.PartNo'),
                DB::raw('Inventory.InvName'),
                'AssetMutationKB.Begin',
                'AssetMutationKB.In',
                'AssetMutationKB.Out',
                'AssetMutationKB.Unit',
                'AssetMutationKB.Adjustment',
                'AssetMutationKB.Ending',
                'AssetMutationKB.StockTaking',
                'AssetMutationKB.Variance'
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
