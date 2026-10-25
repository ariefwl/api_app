<?php

namespace App\Http\Controllers\KaBer;

use App\Http\Controllers\Controller;
use App\Models\KaBer\Purchase;
use App\Models\KaBer\Ware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{

    public function getPurchaseData(Request $request)
    {
        $columns = [
            0 => 'PurchaseKB.DocType',
            1 => 'PurchaseKB.PIBNo',
            2 => 'PurchaseKB.PIBDate',
            3 => 'PurchaseKB.GRNo',
            4 => 'PurchaseKB.GRDate',
            5 => 'Supplier.[Supplier Name]',
            6 => 'Inventory.PartNo',
            7 => 'Inventory.InvName',
            8 => 'PurchaseKB.Unit',
            9 => 'PurchaseKB.Qty',
            10 => 'PurchaseKB.Currency',
            11 => 'PurchaseKB.Amount',
            12 => 'PurchaseKB.Up',
            13 => 'PurchaseKB.Rate',
            14 => 'PurchaseKB.ConUp',
            15 => 'PurchaseKB.ConAmount',
            16 => 'PurRecDet.InvNo',
            17 => 'Ware.[Warehouse Name]',
        ];

        $period = $request->input('period');
        $wareId = $request->input('wareId');

        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);

        // DataTables mengirim search sebagai objek dengan properti value.
        $search = trim((string) $request->input('search', ''));

        // Query dasar: samakan kolom dan JOIN dengan DBeaver.
        $baseQuery = Purchase::query()
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
            ->leftJoin(
                'JIData.dbo.Ware as Ware',
                'PurchaseKB.WareID',
                '=',
                'Ware.WareId'
            )
            ->join(
                'JIData.dbo.PurRec as PurRec',
                'PurchaseKB.GRNo',
                '=',
                'PurRec.GRNo'
            )
            ->join(
                'JIData.dbo.PurRecDet as PurRecDet',
                'PurRec.IDNo',
                '=',
                'PurRecDet.IDNo'
            )
            ->where('PurchaseKB.Period', $period)
            ->where('PurRecDet.PickBill', 1)
            ->when(
                $wareId !== null && $wareId !== '',
                function ($query) use ($wareId) {
                    $query->where('PurchaseKB.WareID', $wareId);
                }
            )
            ->select([
                'PurchaseKB.DocType',
                'PurchaseKB.PIBNo',
                'PurchaseKB.PIBDate',
                'PurchaseKB.GRNo',
                'PurchaseKB.GRDate',
                DB::raw(
                    'Supplier.[Supplier Name] AS [Supplier Name]'
                ),
                'Inventory.PartNo',
                'Inventory.InvName',
                'PurchaseKB.Unit',
                'PurchaseKB.Qty',
                'PurchaseKB.Currency',
                'PurchaseKB.Amount',
                'PurchaseKB.Up',
                'PurchaseKB.Rate',
                'PurchaseKB.ConUp',
                'PurchaseKB.ConAmount',
                'PurRecDet.InvNo',
                DB::raw(
                    'Ware.[Warehouse Name] AS [Warehouse Name]'
                ),
            ])
            ->distinct();

        // Hitung seluruh baris unik sebelum pencarian.
        $totalData = DB::query()
            ->fromSub(clone $baseQuery, 'purchase_results')
            ->count();

        // Terapkan pencarian jika diisi.
        $filteredQuery = clone $baseQuery;

        if (!empty($search)) {
            $filteredQuery->where(function ($q) use ($search) {
                $keyword = "%{$search}%";

                $q->where('Inventory.PartNo', 'LIKE', $keyword)
                    ->orWhere('Inventory.InvName', 'LIKE', $keyword)
                    ->orWhere('PurchaseKB.GRNo', 'LIKE', $keyword)
                    ->orWhere('PurchaseKB.PIBNo', 'LIKE', $keyword)
                    ->orWhere('PurRecDet.InvNo', 'LIKE', $keyword)
                    ->orWhereRaw(
                        '[Supplier].[Supplier Name] LIKE ?',
                        [$keyword]
                    );
            });
        }

        // Hitung baris unik setelah pencarian.
        $totalFiltered = DB::query()
            ->fromSub(clone $filteredQuery, 'filtered_results')
            ->count();

        // Urutkan dan ambil halaman data.
        $dataQuery = clone $filteredQuery;

        $orderColumnIndex = (int) $request->input(
            'order.0.column',
            3
        );

        $orderColumn = $columns[$orderColumnIndex]
            ?? 'PurchaseKB.GRNo';

        $orderDir = strtolower(
            $request->input('order.0.dir', 'asc')
        );

        if (!in_array($orderDir, ['asc', 'desc'], true)) {
            $orderDir = 'asc';
        }

        $dataQuery
            ->orderBy($orderColumn, $orderDir)
            ->orderBy('PurchaseKB.GRNo', 'asc')
            ->orderBy('Inventory.PartNo', 'asc');

        if ($length !== -1) {
            $dataQuery->offset($start)->limit(max(1, $length));
        }

        $data = $dataQuery->get();

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }


    public function countItem($period)
    {
        $total = Purchase::where('Period', $period)->count();

        return response()->json([
            'total' => $total
        ]);
    }

    public function getWareHouses(Request $request)
    {
        $data = Ware::query()
            ->select(['WareId', 'Warehouse Name as WarehouseName'])
            ->where('KB', 1)
            ->orderBy('WareId', 'desc')
            ->get();

        return response()->json([
            'data' => $data,
        ]);
    }
}
