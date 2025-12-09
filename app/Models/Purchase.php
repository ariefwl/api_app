<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function Laravel\Prompts\select;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'BC_Data.dbo.Purchase';
    protected $primaryKey = 'IDNo';
    public $timestamps = false;

    // protected $appends = ['order_no', 'inv_no'];

    // relasi ke table Inventory
    public function inventory()
    {
        return $this->belongsTo(Inventory::class,'ItemCode','PartNo');
    }

    // relasi ke tabel Facility
    public function facility()
    {
        return $this->belongsTo(Facilities::class,'FacilitiesID','FacilitiesID');
    }

    // get OrderNo
    public function getOrderNoAttribute()
    {
        $purRecDetail = PurchaseReceiveDetail::from('JIData.dbo.PurRecDet as prd')
           ->select('prd.OrderNo')
           ->leftJoin('JIData.dbo.PurRec as pr', 'pr.IDNo', '=', 'prd.IDNo')
           ->where('pr.GRNo', $this->GRNo)
           ->orderBy('prd.IDNo')
           ->first();

        return $purRecDetail ? $purRecDetail->OrderNo : null;
    }

    public function getInvNoAttribute()
    {
        $purRecDetail = PurchaseReceiveDetail::from('JIData.dbo.PurRecDet as prd')
           ->select('prd.InvNo')
           ->leftJoin('JIData.dbo.PurRec as pr', 'pr.IDNo', '=', 'prd.IDNo')
           ->where('pr.GRNo', $this->GRNo)
           ->orderBy('prd.IDNo')
           ->first();

        return $purRecDetail ? $purRecDetail->InvNo : null;
    }

    public function scopeWithSelectedFields($query)
    {
        return $query->select([
            'IDNo',
            'DocType', 
            'PIBNo', 
            'PIBDate', 
            'SerialNo', 
            'GRNo', 
            'GRDate', 
            'ItemCode', 
            'Qty', 
            'Unit', 
            'Currency', 
            'Up', 
            'Amount', 
            'SubContract', 
            'Origin', 
            'Period', 
            'Rate', 
            'ConUp', 
            'ConAmount',
            'WarehouseName',
            'FacilitiesID'
        ])->with([
            'facility:FacilitiesID,FacilitiesName',
            'inventory:PartNo,HSCode,InvName'
        ]);
    }

    
    public function scopeForPeriod($query, $period)
    {
        return $query->where('Period', $period);
    }
}
