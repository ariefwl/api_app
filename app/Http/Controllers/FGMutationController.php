<?php

namespace App\Http\Controllers;

use App\Http\Resources\FGMutationResource;
use App\Models\FGMutation;
use Illuminate\Http\Request;

class FGMutationController extends Controller
{
    public function getFGMutationData($period)
    {
        $FGMutation = FGMutation::SelectedFields()
            ->Periode($period)
            ->get();
        
        return FGMutationResource::collection($FGMutation);
    }
}
