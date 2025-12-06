<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waste extends Model
{
    use HasFactory;

    protected $table = 'BC_Data.dbo.Waste';
    protected $primaryKey = 'IDNo';

    public function inventory()
    {
        return $this->belongsTo(Inventory::class,'ItemCode','PartNo');
    }

    public function facilities()
    {
        return $this->belongsTo(Facilities::class, 'FacilitiesID', 'FacilitiesID');
    }

    public function scopeSelectedFields($query)
    {
        return $query->select(['NoTr','DateTr','ItemCode','Unit','Qty','Up','Amount','FacilitiesID'])->with(['inventory:PartNo,InvName','facilities:FacilitiesID,FacilitiesName']);
    }

    public function scopePeriode($query, $period)
    {
        return $query->where('Period', $period);
    }
}
