<?php

namespace App\Models\KaBer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReceiveDetail extends Model
{
    use HasFactory;

    protected $table = 'JIData.dbo.PurRecDet';
    protected $primaryKey = 'IDNo';
    public $timestamps = false;

    public function purchaseReceive()
    {
        return $this->hasMany(PurchaseReceive::class, 'IDNo', 'IDNo');
    }
}
