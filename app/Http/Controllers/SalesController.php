<?php

namespace App\Http\Controllers;

use App\Http\Resources\SalesResource;
use App\Models\Sales;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function getSalesData($period)
    {
        $Sales = Sales::selectedFields()
           ->Periode($period)
           ->get();

        return SalesResource::collection($Sales);
    }

    public function countItem($period)
    {
        $total = Sales::where('Period', $period)->count();

        return response()->json([
            'total' => $total
        ]);
    }
}
