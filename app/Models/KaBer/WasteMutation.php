<?php

namespace App\Models\KaBer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteMutation extends Model
{
    use HasFactory;
    protected $table = 'BC_Data.dbo.WasteMutationKB';
    protected $primaryKey = 'IDNo';
    public $timestamps = false;
}
