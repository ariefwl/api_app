<?php

namespace App\Models\KaBer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'JIData.dbo.Supplier';
    protected $primaryKey = 'SupplierID';
    public $timestamps = false;

    public function purchase()
    {
        return $this->hasMany(Purchase::class, 'SupplierId', 'SupplierID');
    }
}
