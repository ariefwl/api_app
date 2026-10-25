<?php

namespace App\Http\Controllers;

use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function getPurchaseData(Request $request)
    {
        $columns = [
            0 => 'IDNo',
            1 => 'DocType',
            2 => 'PIBNo',
            3 => 'PIBDate',
            4 => 'SerialNo',
            5 => 'GRNo',
            6 => 'GRDate',
            7 => 'ItemCode',
            8 => 'Qty',
            9 => 'Unit',
            10 => 'Currency',
            11 => 'Up',
            12 => 'Amount',
            13 => 'SubContract',
            14 => 'Origin',
            15 => 'Period',
            16 => 'Rate',
            17 => 'ConUp',
            18 => 'ConAmount',
            19 => 'WarehouseName',
            20 => 'FacilitiesID',
        ];

        $length = $request->input('length');
        $start = $request->input('start');
        $search = $request->input('search');
        $period = $request->period;

        $orderColumnIndex = $request->input('order.0.column');
        $orderColumn = $columns[$orderColumnIndex] ?? 'IDNo';
        $orderDir = $request->input('order.0.dir', 'asc');

        $query = Purchase::SelectedFields()
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
            ->orderBy($orderColumn, $orderDir)
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
        $total = Purchase::where('Period', $period)->count();

        return response()->json([
            'total' => $total
        ]);
    }
}
