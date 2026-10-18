<?php

namespace App\Http\Controllers;

use App\Http\Resources\FGMutationResource;
use App\Models\FGMutation;
use Illuminate\Http\Request;

class FGMutationController extends Controller
{
    public function getFGMutationData(Request $request)
    {
        $columns = [
            0 => 'IDNo',
            1 => 'ItemCode',
            2 => 'InvName',
            3 => 'Unit',
            4 => 'Begin',
            5 => 'In',
            6 => 'Out',
            7 => 'Ending'
        ];

        $length = $request->input('length');
        $start  = $request->input('start');
        $search = $request->input('search');
        $period = $request->period;

        $orderColumnIndex = $request->input('order.0.column');
        $orderColumn = $columns[$orderColumnIndex] ?? 'IDNo';
        $orderDir = $request->input('order.0.dir','asc');

        $query = FGMutation::SelectedFields()
            ->Periode($period);

        $totalData = $query->count();

        if (!empty($search)) {

        $query->where(function ($q) use ($search) {
                $q->where('ItemCode', 'LIKE', "%{$search}%")
                ->orWhere('Unit', 'LIKE', "%{$search}%")
                ->orWhereHas('inventory', function ($inv) use ($search) {
                    $inv->where('InvName', 'LIKE', "%{$search}%");
                });
            });
        }

        $totalFiltered = $query->count();

        $data = $query
            ->orderBy($orderColumn,$orderDir)
            ->offset($start)
            ->limit($length)
            ->get();

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        ]);
    }

    public function countItem($period)
    {
        $total = FGMutation::where('Period', $period)->count();

        return response()->json([
            'total' => $total
        ]);
    }

    public function exportFGMutation(Request $request)
    {
        $period = $request->period;

        $data = FGMutation::SelectedFields()
            ->Periode($period)
            ->get();
        return FGMutationResource::collection($data);
    }
}
