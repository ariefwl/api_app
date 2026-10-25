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
        // Index 0 = kolom No (row number, tidak ada di DB)
        // Index 1–5 = kolom data sebenarnya
        $columns = [
            1 => 'Inventory.PartNo',
            2 => 'Inventory.InvName',
            3 => 'WIPKBDetail.Unit',
            4 => 'WIPKBDetail.Qty',
            5 => 'WIPKBDetail.Remark',
        ];

        $length = (int) $request->input('length', 10);
        $start = (int) $request->input('start', 0);
        $search = $request->input('search.value');
        $period = $request->input('period');

        // Sorting dari DataTables
        $orderColumnIndex = (int) $request->input('order.0.column', 1);
        $orderDir = $request->input('order.0.dir', 'asc');

        // Pastikan direction valid
        $orderDir = in_array(strtolower($orderDir), ['asc', 'desc']) ? $orderDir : 'asc';

        // Ambil nama kolom dari mapping, default ke PartNo
        $orderColumn = $columns[$orderColumnIndex] ?? 'Inventory.PartNo';

        $query = WIP::query()
            ->join(
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
                $q->where('Inventory.PartNo', 'LIKE', "%{$search}%")
                    ->orWhere('Inventory.InvName', 'LIKE', "%{$search}%")
                    ->orWhere('WIPKBDetail.Remark', 'LIKE', "%{$search}%");
            });
        }

        $totalFiltered = (clone $query)->count();

        $data = $query
            ->orderBy($orderColumn, $orderDir)
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
