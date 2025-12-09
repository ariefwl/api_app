<?php

namespace App\Http\Controllers;

use App\Http\Resources\FinGoodsResource;
use App\Models\FinishedGoods;
use Illuminate\Http\Request;

class FinGoodsController extends Controller
{
    public function getFinGoodsData($period)
    {
        $FinGoods = FinishedGoods::selectedFields()
            ->forPeriod($period)
            ->get();
        
        return FinGoodsResource::collection($FinGoods);
    }

    public function countItem($period)
    {
        $total = FinishedGoods::where('Period', $period)->count();

        return response()->json([
            'total' => $total
        ]);
    }
}
