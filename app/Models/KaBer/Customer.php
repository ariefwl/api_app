<?php

namespace App\Models\KaBer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'JIData.dbo.Customer';
    protected $primaryKey = 'CustomerID';
    public $timestamps = false;


}
