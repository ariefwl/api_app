<?php

namespace App\Http\Controllers;

use App\Http\Resources\MatUseResource;
use Illuminate\Http\Request;
use App\Models\MaterialUse;

class MatUseController extends Controller
{
    public function getMaterialUseData($period)
    {
        $MaterialUse = MaterialUse::withSelectedFields()
           ->forPeriod($period)
           ->get();
        
        return MatUseResource::collection($MaterialUse);
    }

    public function countItem($period)
    {
        $total = MaterialUse::where('Period', $period)->count();

        return response()->json([
            'total' => $total
        ]);
    }
}
