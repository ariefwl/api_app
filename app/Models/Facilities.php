<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facilities extends Model
{
    use HasFactory;

    protected $table = 'BC_Data.dbo.facilities';
    protected $primaryKey = 'FacilitiesID';
    public $timestamps = false;
}
