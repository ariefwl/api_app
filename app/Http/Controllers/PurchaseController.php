<?php

namespace App\Http\Controllers;

use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public $period = '202401';

    public function getPurchaseData($period)
    {
        $purchases = Purchase::withSelectedFields()
           ->forPeriod($period)
           ->get();
        
        return PurchaseResource::collection($purchases);
    }
}
