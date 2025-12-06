<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
            'PIBNo' => $this->PIBNo,
            'PIBDate' => $this->PIBDate,
            'SerialNo' => $this->SerialNo,
            'GRNo' => $this->GRNo,
            'GRDate' => $this->GRDate,
            'HSCode' => $this->inventory->HSCode ?? null,
            'ItemCode' => $this->ItemCode,
            'InvName' => $this->inventory->InvName ?? null,
            'Qty' => $this->Qty,
            'Unit' => $this->Unit,
            'Currency' => $this->Currency,
            'Up' => $this->Up,
            'Amount' => $this->Amount,
            'SubContract' => $this->SubContract,
            'Origin' => $this->Origin,
            'Period' => $this->Period,
            'Rate' => $this->Rate,
            'ConUp' => $this->ConUp,
            'ConAmount' => $this->ConAmount,
            'FacilitiesName' => $this->facility->FacilitiesName ?? null,
            'OrderNo' => $this->order_no,
            'InvNo' => $this->inv_no,
        ];
    }
}
