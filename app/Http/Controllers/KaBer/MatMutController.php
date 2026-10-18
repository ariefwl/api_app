<?php

namespace App\Http\Controllers\KaBer;

use App\Http\Controllers\Controller;
use App\Models\KaBer\MaterialMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatMutController extends Controller
{
    public function getMatMutData(Request $request)
    {
        $columns = [
            0 => 'MatMutKB.IDNo',
            1 => 'Inventory.PartNo',
            2 => 'Inventory.InvName',
            3 => 'MatMutKB.Unit',
            4 => 'MatMutKB.Begin',
            5 => 'MatMutKB.In',
            6 => 'MatMutKB.Out',
            7 => 'MatMutKB.Adjustment',
            8 => 'MatMutKB.Ending',
            9 => 'MatMutKB.StockTaking',
        ];

        $length = (int) $request->input('length', 10);
        $start = (int) $request->input('start', 0);
        $search = $request->input('search.value');
        $period = $request->input('period');

        $query = MaterialMutation::query()
            ->from('BC_Data.dbo.MaterialMutationKB as MatMutKB')
            ->leftJoin(
                'JIData.dbo.Inventory as Inventory',
                'MatMutKB.InvID',
                '=',
                'Inventory.InvId'
            )
            ->where('MatMutKB.Period', $period)
            ->select([
                'MatMutKB.IDNo',
                DB::raw('Inventory.PartNo'),
                DB::raw('Inventory.InvName'),
                'MatMutKB.Unit',
                'MatMutKB.Begin',
                'MatMutKB.In',
                'MatMutKB.Out',
                'MatMutKB.Adjustment',
                'MatMutKB.Ending',
                'MatMutKB.StockTaking'
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
