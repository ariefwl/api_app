<?php

namespace App\Models\KaBer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $table = 'BC_Data.dbo.SalesKB';
    protected $primaryKey = 'IDNo';
    public $timestamps = false;

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }

    public function scopePeriode($query, $period)
    {
        return $query->where('SalesKB.Period', $period);
    }
}
