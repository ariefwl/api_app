<?php

namespace App\Http\Controllers\KaBer;

use App\Http\Controllers\Controller;
use App\Models\KaBer\WIP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class wipController extends Controller
{
    public function getWIPData(Request $request)
    {
        $columns = [
            0 => 'Inventory.PartNo',
            1 => 'Inventory.InvName',
            2 => 'WIPKBDetail.Unit',
            3 => 'WIPKBDetail.Qty',
            4 => 'WIPKBDetail.Remark',
        ];

        $length = (int) $request->input('length', 10);
        $start = (int) $request->input('start', 0);
        $search = $request->input('search.value');
        $period = $request->input('period');

        $query = WIP::query()
            ->leftJoin(
                'JIData.dbo.Inventory as Inventory',
                'WIPKBDetail.InvID',
                '=',
                'Inventory.InvId'
            )
            ->where('WIPKBDetail.Period', $period)
            ->select([
                DB::raw('Inventory.PartNo'),
                DB::raw('Inventory.InvName'),
                'WIPKBDetail.Unit',
                'WIPKBDetail.Qty',
                'WIPKBDetail.Remark'
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
