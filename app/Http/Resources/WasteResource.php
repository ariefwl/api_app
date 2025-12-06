<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WasteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'NoTr' => $this->NoTr,
            'DateTr' => $this->DateTr,
            'ItemCode' => $this->ItemCode,
            'InvName' => $this->inventory->InvName,
            'Unit' => $this->Unit,
            'Qty' => $this->Qty,
            'Up' => $this->Up,
            'Amount' => $this->Amount,
            'FacilitiesName' => $this->facilities->FacilitiesName ?? null
        ];
    }
}
