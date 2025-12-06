<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatUseResource extends JsonResource
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
            'MUNo' => $this->MUNo,
            'MUDate' => $this->MUDate,
            'ItemCode' => $this->ItemCode,
            'InvName' => $this->inventory->InvName ?? null,
            'Unit' => $this->Unit,
            'Qty' => $this->Qty,
            'SubConQty' => $this->SubConQty,
            'SubContract' => $this->SubContract,
            'FacilitiesName' => $this->facility->FacilitiesName ?? null,
            'WasteQty' => $this->WasteQty
        ];
    }
}
