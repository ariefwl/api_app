<?php

namespace App\Models\KaBer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function Laravel\Prompts\table;

class Ware extends Model
{
    use HasFactory;
    protected $table = 'JIData.dbo.Ware';
    protected $primaryKey = 'WareId';
    public $timestamps = false;

    public function purchase()
    {
        return $this->hasMany(Purchase::class, 'WareID', 'WareId');
    }
}
