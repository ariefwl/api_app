<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishedGoods extends Model
{
    use HasFactory;

    protected $table = 'BC_Data.dbo.FinishedGoods';
    protected $primaryKey = 'IDNo';
    public $timestamps = false;

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'ItemCode', 'PartNo');
    }

    public function scopeSelectedFields($query)
    {
        return $query->select(['IDNo','FGNo','FGDate','ItemCode','Unit','Qty','SubContract','WarehouseName'])->with(['inventory:PartNo,InvName']);
    }

    public function scopeForPeriod($query, $period)
    {
        return $query->where('Period', $period);
    }
}
