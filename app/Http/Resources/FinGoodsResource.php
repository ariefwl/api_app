<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinGoodsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'IDNo' => $this->IDNo,
            'FGNo' => $this->FGNo,
            'FGDate' => $this->FGDate,
            'ItemCode' => $this->ItemCode,
            'InvName' => $this->inventory->InvName ?? null,
            'Unit' => $this->Unit,
            'Qty' => $this->Qty,
            'SubContract' => $this->SubContract,
            'SubConQty' => $this->SubConQty,
            'WarehouseName' => $this->WarehouseName
        ];
    }
}
