<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatMutResource extends JsonResource
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
            'FacilitiesName' => $this->facilities->FacilitiesName ?? null,
            'ItemCode' => $this->ItemCode,
            'InvName' => $this->inventory->InvName,
            'Unit' => $this->Unit,
            'Begin' => $this->Begin,
            'In' => $this->In,
            'Out' => $this->Out,
            'Ending' => $this->Ending,
            'WarehouseName' => $this->WarehouseName
        ];
    }
}
