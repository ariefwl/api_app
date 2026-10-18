<?php

namespace App\Http\Controllers;

use App\Http\Resources\FinGoodsResource;
use App\Models\FinishedGoods;
use Illuminate\Http\Request;

class FinGoodsController extends Controller
{
    public function getFinGoodsData(Request $request)
    {
        $columns = [
            0 => 'IDNo',
            1 => 'FGNo',
            2 => 'FGDate',
            3 => 'ItemCode',
            4 => 'InvName',
            5 => 'Unit',
            6 => 'Qty',
            7 => 'SubContract',
            8 => 'SubConQty',
            9 => 'WarehouseName'
        ];

        $length = $request->input('length');
        $start  = $request->input('start');
        $search = $request->input('search');
        $period = $request->period;

        $orderColumnIndex = $request->input('order.0.column');
        $orderColumn = $columns[$orderColumnIndex] ?? 'IDNo';
        $orderDir = $request->input('order.0.dir','asc');

        $query = FinishedGoods::SelectedFields()
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
        $total = FinishedGoods::where('Period', $period)->count();

        return response()->json([
            'total' => $total
        ]);
    }

    public function exportFinGoods(Request $request)
    {
        $period = $request->period;

        $data = FinishedGoods::SelectedFields()
            ->Periode($period)
            ->get();
        return FinGoodsResource::collection($data);
    }
}
