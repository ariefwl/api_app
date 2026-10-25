<?php

namespace App\Models\KaBer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReceive extends Model
{
    use HasFactory;

    protected $table = 'JIData.dbo.PurRec';
    protected $primaryKey = 'IDNo';
    public $timestamps = false;

    public function purchaseReceiveDetail()
    {
        return $this->belongsTo(PurchaseReceiveDetail::class, 'IDNo', 'IDNo');
    }
}
