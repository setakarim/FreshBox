<?php

namespace App\Http\Controllers\Finance;

use App\Http\Resources\Finance\InvoiceOrderResource;
use App\Http\Resources\Warehouse\DeliveryOrderResource;
use App\Model\Finance\InvoiceOrder;
use App\Model\Marketing\SalesOrder;
use App\Model\Warehouse\DeliveryOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class FormInvoiceOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $searchValue = $request->input('search');
        $columns = [
            array('title' => 'Invoice Order No', 'field' => 'invoice_no'),
            array('title' => 'Delivery Order No', 'field' => 'delivery_order_no'),
            array('title' => 'Sales Order No', 'field' => 'sales_order_no'),
            array('title' => 'Customer', 'field' => 'customer_name'),
            array('title' => 'Invoice Date', 'field' => 'invoice_date_formatted'),
            array('title' => 'Total Amount', 'field' => 'total_price'),
            array('title' => 'Created By', 'field' => 'created_by_name'),
            array('title' => 'Created At', 'field' => 'created_at'),
            array('title' => 'Status', 'field' => 'status_name'),
        ];

        $config = [
            //Title Required
            'title' => 'List Invoice Order',
            //Search Route Required
            'route-search' => 'admin.finance.invoice_order.index',
            /**
             * Route Can Be Null, Using Route Name
             */
            //Route For Button Add
            'route-add' => 'admin.finance.invoice_order.create',
            //Route For Button View
            'route-view' => 'admin.finance.invoice_order.print',
        ];

        $query = InvoiceOrder::dataTableQuery($searchValue);
        $data = $query->paginate(10);

        return view('admin.crud.index', compact('columns', 'data', 'config'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $delivery_order = DeliveryOrder::whereHas('sales_order', function ($q) {
                $q->where('status', 5);
            })->get();
            return DeliveryOrderResource::collection($delivery_order);
        }
        $config = [
            'vue-component' => '<add-invoice></add-invoice>'
        ];
        return view('layouts.vue-view', compact('config'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $rules = [
            'invoice_date' => 'required',
            'do_id' => 'required',
        ];

        $request->validate($rules);

        $so_id = $request->so_id;
        $invoice_order = [
            'do_id' => $request->do_id,
            'user_id' => $request->user_id,
            'invoice_date' => $request->invoice_date,
            'invoice_no' => $this->generateInvoiceNo(),
            'created_by' => $request->user_id
        ];
        InvoiceOrder::create($invoice_order);
        SalesOrder::find($so_id)->update(['status' => 6]);

        return response()->json([
            'status' => 'success'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Generate Invoice No.
     *
     * @return string
     */
    public function generateInvoiceNo()
    {
        $year_month = Carbon::now()->format('ym');
        $latest_invoice_order = InvoiceOrder::where(DB::raw("DATE_FORMAT(created_at, '%y%m')"), $year_month)->latest()->first();
        $get_last_inv_no = isset($latest_invoice_order->invoice_no) ? $latest_invoice_order->invoice_no : 'INV' . $year_month . '00000';
        $cut_string_inv_no = str_replace("INV", "", $get_last_inv_no);
        return 'INV' . ($cut_string_inv_no + 1);
    }

    /**
     * Generate Invoice No.
     *
     * @param $id
     * @return InvoiceOrderResource
     */
    public function print($id)
    {
        if (request()->ajax()) {
            return new InvoiceOrderResource(InvoiceOrder::find($id));
        }
        $config = [
            'vue-component' => "<print-invoice-order :id='$id'></print-invoice-order>"
        ];
        return view('layouts.vue-view', compact('config'));
    }

}
