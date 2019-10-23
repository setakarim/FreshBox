<?php

namespace App\Model\Procurement;

use App\Model\MasterData\Uom;
use App\MyModel;
use App\Traits\SearchTraits;

class ListProcurementDetail extends MyModel
{
    use SearchTraits;

    protected $table = 'trx_list_procurement_detail';
    protected $fillable = ['trx_list_procurement_id', 'trx_assign_procurement_id', 'qty', 'qty_minus', 'uom_id', 'amount', 'status', 'created_by', 'created_at'];
    protected $appends = [
        'item_name',
        'uom_name',
        'status_name',
    ];

    public function ListProcurement()
    {
        return $this->belongsTo(ListProcurement::class);
    }

    public function AssignProcurement()
    {
        return $this->belongsTo(AssignProcurement::class, 'trx_assign_procurement_id', 'id');
    }

    public function Uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id', 'id');
    }

    public function getItemNameAttribute()
    {
        if (isset($this->AssignProcurement->Item->name_item)) {
            return $this->AssignProcurement->Item->name_item;
        } else {
            return '';
        }
    }

    public function getUomNameAttribute()
    {
        if (isset($this->Uom->name)) {
            return $this->Uom->name;
        } else {
            return '';
        }
    }

    public function getStatusNameAttribute()
    {
        if ($this->status === 1) {
            return '<span class="badge badge-info">Submit</span>';
        } elseif ($this->status === 2) {
            return '<span class="badge badge-success">Receive</span>';
        } elseif ($this->status === 3) {
            return '<span class="badge badge-danger">Return</span>';
        } else {
            return 'Status NotFound';
        }
    }
}
