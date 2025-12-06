<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $table = 'BC_Data.dbo.Sales';
    protected $primaryKey = 'IDNo';
    public $timestamps = false;

    public function inventory()
    {
        return $this->belongsTo(Inventory::class,'ItemCode','PartNo');
    }

    public function scopeSelectedFields($query)
    {
        return $query->select(['IDNo','DocType','PEBNo','PEBDate','DONo','DODate','CustomerName','Origin','ItemCode','Qty','Unit','Currency','Up','Amount'])->with(['inventory:PartNo,InvName']);
    }

    public function scopePeriode($query, $period)
    {
        return $query->where('Period', $period);
    }
}
