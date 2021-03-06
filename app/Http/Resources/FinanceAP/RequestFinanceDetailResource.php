<?php

namespace App\Http\Resources\FinanceAP;

use App\Model\MasterData\Vendor;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestFinanceDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $vendor = Vendor::find($this->supplier_id);

        return [
            'id' => $this->id,
            'requestFinanceId' => $this->request_finance_id,
            'item_name' => $this->item_name,
            'skuid' => $this->skuid,
            'qty' => $this->qty,
            'uom_id' => $this->uom_id,
            'uom_name' => $this->Uom->name,
            'price' => $this->price,
            'ppn' => $this->ppn,
            'pph' => $this->pph,
            'total' => $this->total,
            'price_confirm' => 0,
            'total_confirm' => 0,
            'supplier_name' => isset($vendor) ? $vendor->name : '',
            'supplier_id' => $this->supplier_id,
            'remarks' => $this->remarks,
            'checked' => $this->checked,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
