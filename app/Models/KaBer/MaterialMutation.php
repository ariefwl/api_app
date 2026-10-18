<?php

namespace App\Models\KaBer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialMutation extends Model
{
    use HasFactory;

    protected $table = 'BC_Data.dbo.MaterialMutationKB';
    protected $primaryKey = 'IDNo';
    public $timestamps = false;

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'InvID', 'InvId');
    }

    public function scopePeriode($query, $period)
    {
        return $query->where('MatMutKB.Period', $period);
    }
}
