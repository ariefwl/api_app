<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesResource extends JsonResource
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
            'DocType' => $this->DocType,
            'PEBNo' => $this->PEBNo,
            'PEBDate' => $this->PEBDate,
            'DONo' => $this->DONo,
            'DODate' => $this->DODate,
            'CustomerName' => $this->CustomerName,
            'Origin' => $this->Orgin,
            'ItemCode' => $this->ItemCode,
            'InvName' => $this->inventory->InvName,
            'Qty' => $this->Qty,
            'Unit' => $this->Unit,
            'Currency' => $this->Currency,
            'Up' => $this->Up,
            'Amount' => $this->Amount
        ];
    }
}
