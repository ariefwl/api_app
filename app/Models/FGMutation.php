<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FGMutation extends Model
{
    use HasFactory;

    protected $table = 'BC_Data.dbo.FGMutation';
    protected $primaryKey = 'IDNo';
    public $timestamps = false;

    public function inventory()
    {
        return $this->belongsTo(Inventory::class,'ItemCode','PartNo');
    }

    public function scopeSelectedFields($query)
    {
        return $query->select(['IDNo','ItemCode','Unit','Begin','In','Out','Ending','WarehouseName'])->with(['inventory:PartNo,InvName']);
    }

    public function scopePeriode($query, $period)
    {
        return $query->where('Period', $period);
    }
}
