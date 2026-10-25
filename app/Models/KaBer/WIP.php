<?php

namespace App\Models\KaBer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WIP extends Model
{
    use HasFactory;

    protected $table = 'BC_Data.dbo.WIPKBDetail';
    protected $primaryKey = 'InvID';
    public $timestamps = false;

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'InvId', 'InvID');
    }

    public function scopePeriode($query, $period)
    {
        return $query->where('WIPKBDetail.Period', $period);
    }
}
