<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'JIData.dbo.Inventory';
    protected $primaryKey = 'InvId';
    public $timestamps = false;

    public function purchase()
    {
        return $this->hasMany(Purchase::class,'ItemCode','PartNo' );
    }
}
