<?php

namespace App\Http\Controllers\ApiV1\FinanceAP;

use App\Http\Controllers\Controller;
use App\Model\FinanceAP\RequestFinance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RequestFinanceController extends Controller
{
    public function index()
    {

    }

    public function create()
    {

    }

    public function store(Request $request)
    {

        $data = [
            'user_id' => $request->userId,
            'master_warehouse_id' => $request->warehouseId,
            'no_request' => $request->noRequest,
            'request_date' => $request->requestDate,
            'request_type' => $request->requestType,
            'product_type' => $request->productType
        ];
        RequestFinance::insert($data);
    }

    public function generateSalesOrderNo()
    {
        $year_month = Carbon::now()->format('ym');
//        $latest_sales_order = RequestAdvance::where(DB::raw("DATE_FORMAT(created_at, '%y%m')"), $year_month)->latest()->first();
        $latest_sales_order = '280/GF-FB/7/2019';
//        $get_last_so_no = isset($latest_sales_order->sales_order_no) ? $latest_sales_order->sales_order_no : 'SO' . $year_month . '00000';
        $cut_string_so = str_replace('/GF-FB/7/2019', '', $latest_sales_order);

        return ($cut_string_so + 1) . '/GF-FB/7/2019';
    }


}
