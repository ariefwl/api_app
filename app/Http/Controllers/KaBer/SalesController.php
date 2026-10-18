<?php

namespace App\Http\Controllers\KaBer;

use App\Http\Controllers\Controller;
use App\Models\KaBer\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function getSalesData(Request $request)
    {
        $columns = [
            0 => 'SalesKB.PEBNo',
            1 => 'SalesKB.PEBDate',
            2 => 'SalesKB.DONo',
            3 => 'SalesKB.DODate',
            4 => 'Customer.[Customer Name]',
            5 => 'Inventory.PartNo',
            6 => 'Inventory.InvName',
            7 => 'SalesKB.Qty',
            8 => 'SalesKB.Unit',
            9 => 'SalesKB.Currency',
            10 => 'SalesKB.Up',
            11 => 'SalesKB.Amount',
        ];

        $length = (int) $request->input('length', 10);
        $start = (int) $request->input('start', 0);
        $search = $request->input('search.value');
        $period = $request->input('period');

        $query = Sales::query()
            ->leftJoin(
                'JIData.dbo.Customer as Customer',
                'SalesKB.CustomerID',
                '=',
                'Customer.CustomerID'
            )
            ->leftJoin(
                'JIData.dbo.Inventory as Inventory',
                'SalesKB.InvID',
                '=',
                'Inventory.InvId'
            )
            ->where('SalesKB.Period', $period)
            ->select([
                'SalesKB.PEBNo',
                'SalesKB.PEBDate',
                'SalesKB.DONo',
                'SalesKB.DODate',
                DB::raw('Customer.[Customer Name] AS [Customer Name]'),
                DB::raw('Inventory.PartNo'),
                DB::raw('Inventory.InvName'),
                'SalesKB.Qty',
                'SalesKB.Unit',
                'SalesKB.Currency',
                'SalesKB.Up',
                'SalesKB.Amount',
            ]);

        $totalData = (clone $query)->count();

        // Pencarian DataTables
        // if (!empty($search)) {
        //     $query->where(function ($q) use ($search) {
        //         $q->where('SalesKB.PartNo', 'LIKE', "%{$search}%")
        //             ->orWhere(
        //                 'Supplier.[Supplier Name]',
        //                 'LIKE',
        //                 "%{$search}%"
        //             )
        //             ->orWhereHas('inventory', function ($inv) use ($search) {
        //                 $inv->where(
        //                     'InvName',
        //                     'LIKE',
        //                     "%{$search}%"
        //                 );
        //             });
        //     });
        // }

        $totalFiltered = (clone $query)->count();

        $data = $query
            ->orderBy('SalesKB.PEBNo', 'asc')
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
