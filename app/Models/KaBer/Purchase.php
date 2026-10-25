<?php

namespace App\Models\KaBer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\KaBer\Inventory;
use App\Models\Facilities;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'BC_Data.dbo.PurchaseKB';
    protected $primaryKey = 'IDNo';
    public $timestamps = false;

    // relasi ke Inventory
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'InvID', 'InvId');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'SupplierId', 'SupplierID');
    }

    public function ware()
    {
        return $this->belongsTo(Ware::class, 'WareID', 'WareId');
    }

    public function scopePeriode($query, $period)
    {
        return $query->where('PurchaseKB.Period', $period);
    }
}
