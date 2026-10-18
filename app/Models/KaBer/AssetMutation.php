<?php

namespace App\Models\KaBer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMutation extends Model
{
    use HasFactory;

    protected $table = 'BC_Data.dbo.AssetMutationKB';
    protected $primaryKey = 'IDNo';
    public $timestamps = false;
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'InvID', 'InvId');
    }

    public function scopePeriode($query, $period)
    {
        return $query->where('AssetMutationKB.Period', $period);
    }
}
