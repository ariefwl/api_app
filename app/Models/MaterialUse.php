<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialUse extends Model
{
    use HasFactory;

    protected $table = 'BC_Data.dbo.MaterialUsed';
    protected $primaryKey = 'IDNo';
    protected $guarded = [];
    public $timestamps = false;

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'ItemCode', 'PartNo');
    }

    public function facility()
    {
        return $this->belongsTo(Facilities::class,'FacilitiesID','FacilitiesID');
    }

    public function scopeWithSelectedFields($query)
    {
        return $query->select(['IDNo', 'MUNo', 'MUDate', 'ItemCode', 'Unit', 'Qty', 'SubConQty', 'SubContract', 'FacilitiesID', 'WasteQty'])->with(['inventory:PartNo,InvName','facility:FacilitiesID,FacilitiesName']);
    }

    public function scopeForPeriod($query, $period)
    {
        return $query->where('Period', $period);
    }
}
