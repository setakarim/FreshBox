<?php

namespace App\Model\Procurement;

use App\Model\WarehouseIn\Confirm;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $table = 'notification_procurement';
    protected $fillable = ['status', 'message', 'user_proc_id', 'trx_warehouse_confirm_id', 'read_at', 'created_by', 'created_at'];
    protected $appends = [
        'procurement_no',
    ];

    public function UserProc()
    {
        return $this->belongsTo(UserProcurement::class, 'user_proc_id', 'id');
    }

    public function Confirm()
    {
        return $this->hasMany(Confirm::class, 'trx_warehouse_confirm_id', 'id');
    }

    public function getProcurementNoAttribute()
    {
        if (isset($this->Confirm->ListProcturement->procurement_no)) {
            return $this->Confirm->ListProcturement->procurement_no;
        } else {
            return '';
        }
    }
}