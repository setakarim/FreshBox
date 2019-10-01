<?php


namespace App\Model\Marketing;


use App\Model\MasterData\Item;
use App\Model\MasterData\Uom;
use App\MyModel;

class SalesOrderDetail extends MyModel
{
    protected $table = 'trx_sales_order_detail';
    protected $appends = ['item_name', 'uom_name'];
    protected $fillable = [
        'sales_order_id',
        'qty',
        'skuid',
        'amount_price',
        'total_amount',
        'notes',
        'created_by'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'skuid', 'skuid');
    }

    public function SalesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id', 'id');
    }

    public function getItemNameAttribute()
    {
        if(isset($this->item->name_item)) {
            return $this->item->name_item;
        };
    }

    public function getUomNameAttribute()
    {
        if(isset($this->uom->name)) {
            return $this->uom->name;
        }
    }
}
