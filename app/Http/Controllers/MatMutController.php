<?php

namespace App\Http\Controllers;

use App\Http\Resources\MatMutResource;
use App\Models\MaterialMutation;
use Illuminate\Http\Request;

class MatMutController extends Controller
{
    public function getMatMutData($period)
    {
        $MatMutation = MaterialMutation::SelectedFields()
             ->Periode($period)
             ->get();

        return MatMutResource::collection($MatMutation);
    }
}
