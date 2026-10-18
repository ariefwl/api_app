<?php

namespace App\Http\Controllers\KaBer;

use App\Http\Controllers\Controller;
use App\Models\KaBer\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function getPurchaseData(Request $request)
    {
        $columns = [
            0 => 'PurchaseKB.IDNo',
            1 => 'PurchaseKB.DocType',
            2 => 'PurchaseKB.PIBNo',
            3 => 'PurchaseKB.PIBDate',
            4 => 'PurchaseKB.SerialNo',
            5 => 'PurchaseKB.GRNo',
            6 => 'PurchaseKB.GRDate',
            7 => 'Supplier.[Supplier Name]',
            8 => 'Inventory.PartNo',
            9 => 'Inventory.InvName',
            10 => 'PurchaseKB.Qty',
            11 => 'PurchaseKB.Unit',
            12 => 'PurchaseKB.Currency',
            13 => 'PurchaseKB.Amount',
            14 => 'PurchaseKB.Up',
            15 => 'PurchaseKB.Rate',
            16 => 'PurchaseKB.ConUp',
            17 => 'PurchaseKB.ConAmount'
        ];

        $length = (int) $request->input('length', 10);
        $start = (int) $request->input('start', 0);
        $search = $request->input('search.value');
        $period = $request->input('period');

        // $orderColumnIndex = (int) $request->input('order.0.column', 0);
        // $orderColumn = $columns[$orderColumnIndex] ?? 'PurchaseKB.GRNo';

        // $orderDir = strtolower(
        //     $request->input('order.0.dir', 'asc')
        // );

        // Validasi arah pengurutan
        // if (!in_array($orderDir, ['asc', 'desc'], true)) {
        //     $orderDir = 'asc';
        // }

        $query = Purchase::query()
            ->leftJoin(
                'JIData.dbo.Supplier as Supplier',
                'PurchaseKB.SupplierId',
                '=',
                'Supplier.SupplierID'
            )
            ->leftJoin(
                'JIData.dbo.Inventory as Inventory',
                'PurchaseKB.InvID',
                '=',
                'Inventory.InvId'
            )
            ->where('PurchaseKB.Period', $period)
            ->select([
                'PurchaseKB.IDNo',
                'PurchaseKB.DocType',
                'PurchaseKB.PIBNo',
                'PurchaseKB.PIBDate',
                'PurchaseKB.SerialNo',
                'PurchaseKB.GRNo',
                'PurchaseKB.GRDate',
                DB::raw('Supplier.[Supplier Name] AS [Supplier Name]'),
                DB::raw('Inventory.PartNo'),
                DB::raw('Inventory.InvName'),
                'PurchaseKB.Qty',
                'PurchaseKB.Unit',
                'PurchaseKB.Currency',
                'PurchaseKB.Amount',
                'PurchaseKB.Up',
                'PurchaseKB.Rate',
                'PurchaseKB.ConUp',
                'PurchaseKB.ConAmount'
            ]);

        // Jumlah seluruh data periode sebelum pencarian
        $totalData = (clone $query)->count();

        // Pencarian DataTables
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('PurchaseKB.PartNo', 'LIKE', "%{$search}%")
                    ->orWhere(
                        'Supplier.[Supplier Name]',
                        'LIKE',
                        "%{$search}%"
                    )
                    ->orWhereHas('inventory', function ($inv) use ($search) {
                        $inv->where(
                            'InvName',
                            'LIKE',
                            "%{$search}%"
                        );
                    });
            });
        }

        $totalFiltered = (clone $query)->count();

        $data = $query
            ->orderBy('PurchaseKB.GRNo', 'asc')
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
