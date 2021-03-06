<?php

namespace App\Http\Resources\FinanceAP;

use App\Model\MasterData\Vendor;
use App\User;
use App\UserProfile;
use Illuminate\Http\Resources\Json\JsonResource;
use Riskihajar\Terbilang\Facades\Terbilang;

class SettlementWithDetailResource extends JsonResource
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
        $vendor = Vendor::find($this->requestFinance->vendor_id);
        if ($vendor->type_vendor == 1) {
            //employee
            $user = User::find($vendor->users_id);
            $user_profile = UserProfile::where('user_id', $user->id)->first();
            $dept = isset($user_profile->dept) ? $user_profile->dept : '';
            $user_name = isset($user->name) ? $user->name : '';
            $bank_name = isset($user_profile->bank->name) ? $user_profile->bank->name : '';
            $bank_kode = isset($user_profile->bank->kode_bank) ? $user_profile->bank->kode_bank : '';
            $bank_account = isset($user_profile->bank_account) ? $user_profile->bank_account : '';
            $pic = isset($user->name) ? $user->name : '';
        } else {
            //vendor
            $dept = 'Vendor';
            $user_name = $vendor->name;
            $bank_name = $vendor->bank_name;
            $bank_kode = $vendor->Bank->kode_bank;
            $bank_account = $vendor->bank_account;
            $pic = $vendor->pic_vendor;
        }

        return [
            'id' => $this->id,
            'no_settlement' => $this->no_settlement,
            'shipping_address' => $this->requestFinance->warehouse->address,
            'shipping_name' => $this->requestFinance->warehouse->name,
            'request_date' => $this->created_at->formatLocalized('%d %B %Y'),
            'user_name' => $user_name,
            'dept' => $dept,
            'bank_name' => $bank_name,
            'bank_kode' => $bank_kode,
            'bank_account' => $bank_account,
            'pic' => $pic,
            'total' => $this->requestFinance->total,
            'total_confirm' => $this->total,
            'terbilang' => ucwords(Terbilang::make($this->total).' rupiah'),
            'created_at' => $this->created_at->formatLocalized('%d %B %Y'),
            'created_by_name' => $this->created_by_name,
            'user_id' => $this->requestFinance->vendor_id,

            'details' => SettlementDetailResource::collection($this->detail),
        ];
    }
}
