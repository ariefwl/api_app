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
            17 => 'APInv.TaxNo',
            18 => 'Ware.[Warehouse Name]',
        ];

        $period = $request->input('period');
        $wareIdRaw = $request->input('wareId');
        $wareId = ($wareIdRaw !== null && $wareIdRaw !== '') ? (int) $wareIdRaw : null;

        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);

        // DataTables mengirim search sebagai objek dengan properti value.
        $search = trim((string) $request->input('search', ''));

        $purchaseRows = DB::table('BC_Data.dbo.PurchaseKB as pr')
            ->select('pr.*')
            ->selectRaw('
                ROW_NUMBER() OVER (
                    PARTITION BY
                        pr.GRNo,
                        pr.OrderNo,
                        pr.InvID,
                        pr.Qty
                    ORDER BY pr.IDNo
                ) AS rn
            ')
            ->where('pr.Period', $period)
            ->when(
                $wareId !== null,
                function ($query) use ($wareId) {
                    $query->where('pr.WareID', $wareId);
                }
            );

        $detailRows = DB::table('JIData.dbo.PurRec as pr2')
            ->join(
                'JIData.dbo.PurRecDet as prd',
                'pr2.IDNo',
                '=',
                'prd.IDNo'
            )
            ->select('pr2.GRNo')
            ->addSelect('prd.*')
            ->selectRaw('
                ROW_NUMBER() OVER (
                    PARTITION BY
                        pr2.GRNo,
                        prd.OrderNo,
                        prd.InvId,
                        prd.Qty
                    ORDER BY prd.[LineNo]
                ) AS rn
            ')
            ->where('prd.Qty', '<>', 0);

        $baseQuery = DB::query()
            ->fromSub($purchaseRows, 'PurchaseKB')
            ->leftJoinSub(
                $detailRows,
                'PurRecDet',
                function ($join) {
                    $join->on(
                        'PurRecDet.GRNo',
                        '=',
                        'PurchaseKB.GRNo'
                    )
                        ->on(
                            'PurRecDet.OrderNo',
                            '=',
                            'PurchaseKB.OrderNo'
                        )
                        ->on(
                            'PurRecDet.InvId',
                            '=',
                            'PurchaseKB.InvID'
                        )
                        ->on(
                            'PurRecDet.Qty',
                            '=',
                            'PurchaseKB.Qty'
                        )
                        ->on(
                            'PurRecDet.rn',
                            '=',
                            'PurchaseKB.rn'
                        );
                }
            )
            ->leftJoin(
                'JIData.dbo.APInv as APInv',
                'PurRecDet.InvNo',
                '=',
                'APInv.InvNo'
            )
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
                'APInv.TaxNo',
                DB::raw(
                    'Ware.[Warehouse Name] AS [Warehouse Name]'
                ),
            ]);


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

        $dataQuery->orderBy($orderColumn, $orderDir);

        // Tambahkan secondary sort hanya jika belum sama dengan primary sort
        if ($orderColumn !== 'PurchaseKB.GRNo') {
            $dataQuery->orderBy('PurchaseKB.GRNo', 'asc');
        }
        if ($orderColumn !== 'Inventory.PartNo') {
            $dataQuery->orderBy('Inventory.PartNo', 'asc');
        }

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
            ->select([
                'WareId',
                DB::raw('[Warehouse Name] AS WarehouseName'),
            ])
            ->where('KB', 1)
            ->orderBy('WareId', 'asc')
            ->get();

        return response()->json([
            'data' => $data,
        ]);
    }
}
