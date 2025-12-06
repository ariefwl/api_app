<?php

namespace App\Http\Controllers;

use App\Http\Resources\WasteResource;
use App\Models\Waste;
use Illuminate\Http\Request;

class WasteController extends Controller
{
    public function getWasteData($period)
    {
        $Waste = Waste::SelectedFields()
            ->Periode($period)
            ->get();

        return WasteResource::collection($Waste);
    }
}
