<?php

namespace App\Http\Controllers\ApiV1\Procurement;

use App\Http\Controllers\Controller;
use App\Http\Resources\Procurement\ListProcurementHasItemsResource;
use App\Http\Resources\Procurement\ListProcurementResource;
use App\Model\Marketing\SalesOrderDetail;
use App\Model\MasterData\Vendor;
use App\Model\Procurement\AssignListProcurementDetail;
use App\Model\Procurement\AssignProcurement;
use App\Model\Procurement\AssignSalesOrderDetail;
use App\Model\Procurement\ListProcurement;
use App\Model\Procurement\ListProcurementDetail;
use App\User;
use App\UserProc;
use App\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class ProcurementAPIController extends Controller
{
    /**
     * List All Procurement.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return ListProcurementResource::collection(ListProcurement::all());
    }

    /**
     * List Procurement Not Confirmed.
     *
     * @return AnonymousResourceCollection
     */
    public function listProcurementNotConfirmed()
    {
        return ListProcurementResource::collection(ListProcurement::where('status', 1)->get());
    }

    /**
     * List Procurement Not Confirmed.
     *
     * @return AnonymousResourceCollection
     */
    public function listProcurementConfirmed()
    {
        return ListProcurementResource::collection(ListProcurement::where('status', 2)
        ->where('procurement_no', 'not like', '%R')
        ->get());
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function userProcHasProc()
    {
        $user_proc = UserProc::where('user_id', auth('api')->user()->id)->first();
        $user_proc_id = $user_proc->id;

        $date = Carbon::today()->subDays(7);
        $query = ListProcurement::where('user_proc_id', $user_proc_id)
            ->where('created_at', '>=', $date)
            ->orderBy('id', 'desc')
            ->get();

        return ListProcurementHasItemsResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $rules = [
            'vendor' => 'required',
            'total_amount' => 'required|not_in:0',
            'payment' => 'required',
            'file' => 'required',
            'items' => 'required',
            'items.*.skuid' => 'required',
            'items.*.qty' => 'required|not_in:0',
            'items.*.uom_id' => 'required',
            'items.*.amount' => 'required',
        ];
        $request->validate($rules);

        $user_proc = UserProc::where('user_id', auth('api')->user()->id)->first();
        $user_proc_id = $user_proc->id;

        $vendor = $request->vendor;
        $total_amount = $request->total_amount;
        $payment = $request->payment;
        $remarks = $request->remarks;
        $items = $request->items;

        foreach ($items as $item) {
            $assignProcurement = AssignProcurement::where('user_proc_id', $user_proc_id)
                ->where('status', 1)
                ->where('skuid', $item['skuid'])
                ->get();

            if ($assignProcurement->isEmpty()) {
                return response()->json([
                    'status' => 'Barang Sudah Dibeli',
                ]);
            }
        }

        if ($request->file) {
            $file = $request->file;
            @list($type, $file_data) = explode(';', $file);
            @list(, $file_data) = explode(',', $file_data);
            $file_name = $this->generateProcOrderNo().'.'.explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            Storage::disk('local')->put('public/files/procurement/'.$file_name, base64_decode($file_data), 'public');
        } else {
            $file_name = '';
        }

        if ($user_proc->saldo < $total_amount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Saldo tidak mencukupi',
            ]);
        }

        $user_proc->saldo = intval($user_proc->saldo) - intval($total_amount);
        $user_proc->save();

        $procurement = ListProcurement::create([
            'procurement_no' => $this->generateProcOrderNo(),
            'user_proc_id' => $user_proc_id,
            'vendor' => $vendor,
            'total_amount' => $total_amount,
            'payment' => $payment,
            'file' => $file_name,
            'status' => 1,
            'remarks' => $remarks,
            'created_by' => $user_proc_id,
            'created_at' => Carbon::now(),
        ]);

        foreach ($items as $item) {
            $listProcDetails = ListProcurementDetail::create([
                'trx_list_procurement_id' => $procurement->id,
                'skuid' => $item['skuid'],
                'qty' => $item['qty'],
                'uom_id' => $item['uom_id'],
                'amount' => $item['amount'],
                'status' => 1,
                'created_by' => $user_proc_id,
                'created_at' => Carbon::now(),
            ]);

            $assignProcurement = AssignProcurement::where('user_proc_id', $user_proc_id)
                ->where('status', 1)
                ->where('skuid', $item['skuid'])
                ->get();

            foreach ($assignProcurement as $value) {
                $value->update(['status' => 2]);

                $salesOrderDetail = SalesOrderDetail::find($value->sales_order_detail_id);
                $salesOrderDetail->status = 3;
                $salesOrderDetail->save();

                AssignSalesOrderDetail::create([
                    'sales_order_detail_id' => $value->sales_order_detail_id,
                    'assign_id' => $value->id,
                ]);

                AssignListProcurementDetail::create([
                    'list_procurement_detail_id' => $listProcDetails->id,
                    'assign_id' => $value->id,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function selectBy($id)
    {
        $user_proc = UserProc::where('user_id', auth('api')->user()->id)->first();
        $user_proc_id = $user_proc->id;

        $date = Carbon::today()->subDays(7);
        $query = ListProcurement::where('user_proc_id', $user_proc_id)
            ->where('status', $id)
            ->where('created_at', '>=', $date)
            ->orderBy('id', 'desc')
            ->get();

        return ListProcurementHasItemsResource::collection($query);
    }

    public function reject(Request $request)
    {
        $request->validate([
            'action' => 'required',
            'id' => 'required',
        ]);

        $user_proc = UserProc::where('user_id', auth('api')->user()->id)->first();
        $user_proc_id = $user_proc->id;

        $listProcurement = ListProcurement::find($request->id);

        if ($request->action == 1) {
            $procNo = $listProcurement->procurement_no.'R';

            $procurement = ListProcurement::create([
                'procurement_no' => $procNo,
                'user_proc_id' => $user_proc_id,
                'vendor' => $listProcurement->vendor,
                'total_amount' => 0,
                'payment' => $listProcurement->payment,
                'file' => $listProcurement->file_name,
                'status' => 1,
                'remarks' => $listProcurement->remarks,
                'created_by' => $user_proc_id,
                'created_at' => Carbon::now(),
                ]);

            $listProcurementDetail = ListProcurementDetail::where('trx_list_procurement_id', $request->id)->get();

            foreach ($listProcurementDetail as $item) {
                if ($item->qty_minus > 0) {
                    $listProcDetails = ListProcurementDetail::create([
                        'trx_list_procurement_id' => $procurement->id,
                        'skuid' => $item->skuid,
                        'qty' => $item->qty_minus,
                        'uom_id' => $item->uom_id,
                        'amount' => $item->amount,
                        'status' => 1,
                        'created_by' => $user_proc_id,
                        'created_at' => Carbon::now(),
                    ]);

                    $assignListProcurementDetail = AssignListProcurementDetail::where('list_procurement_detail_id', $item->id)->get();

                    foreach ($assignListProcurementDetail as $itemAssign) {
                        AssignListProcurementDetail::create([
                            'list_procurement_detail_id' => $listProcDetails->id,
                            'assign_id' => $itemAssign->assign_id,
                        ]);
                    }
                }
            }

            $listProcurement->status = 6;
            $listProcurement->save();

            return response()->json([
                'status' => 'success',
            ]);
        } elseif ($request->action == 2) {
            $listProcurement->status = 7;
            $listProcurement->save();

            return response()->json([
                'status' => 'success',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return ListProcurementHasItemsResource
     */
    public function show($id)
    {
        $query = ListProcurement::findOrFail($id);

        return new ListProcurementHasItemsResource($query);
    }

    /**
     * @return string
     */
    public function generateProcOrderNo()
    {
        $year_month = Carbon::now()->format('ym');
        $latest_proc = ListProcurement::where(DB::raw("DATE_FORMAT(created_at, '%y%m')"), $year_month)->latest()->first();
        $get_last_proc_no = isset($latest_proc->procurement_no) ? $latest_proc->procurement_no : 'PROC'.$year_month.'00000';
        $cut_string_proc = str_replace('PROC', '', $get_last_proc_no);

        if (substr($cut_string_proc, -1) == 'R') {
            $cut_string_proc = str_replace('R', '', $cut_string_proc);
        }

        return 'PROC'.($cut_string_proc + 1);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function storeUserProc(Request $request)
    {
        $rules = [
            'name' => 'required',
            'bank' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'category' => 'required',
            'origin' => 'required',
            'bank_account' => 'required',
        ];
        $request->validate($rules);
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $procurement = UserProc::create([
            'user_id' => $user->id,
            'bank_account' => $request->bank_account,
            'bank_id' => $request->bank,
            'saldo' => 5000000,
            'origin_id' => $request->origin,
            'category_id' => $request->category,
            'created_by' => auth('api')->user()->id,
            'created_at' => Carbon::now(),
        ]);
        $role = Role::find(4);
        if ($role) {
            $user->assignRole($role);
        }

        Vendor::create([
            'name' => $request->name,
            'category_id' => $request->category,
            'pic_vendor' => $request->name,
            'tlp_pic' => '0',
            'bank_account' => $request->bank_account,
            'bank_id' => $request->bank,
            'type_vendor' => 1,
            'created_by' => auth('api')->user()->id,
            'created_at' => Carbon::now(),
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'dept' => 'Procurement',
            'bank_account' => $request->bank_account,
            'bank_id' => $request->bank,
            'created_by' => auth('api')->user()->id,
            'created_at' => Carbon::now(),
        ]);

        return response()->json($procurement);
    }

    /**
     * @param $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateUserProc($id, Request $request)
    {
        $rules = [
            'name' => 'required',
            'bank' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'category' => 'required',
            'origin' => 'required',
            'bank_account' => 'required',
        ];
        $request->validate($rules);

        $userProc = UserProc::findOrFail($id);
        $users = User::findOrFail($userProc->user_id);

        $input = $request->all();
        if ($request->password) {
            $input['password'] = bcrypt($input['password']);
        }
        $user = $users->update($input);
        $procurement = $userProc->update([
            'bank_account' => $request->bank_account,
            'bank_id' => $request->bank,
            'origin_id' => $request->origin,
            'category_id' => $request->category,
            'created_by' => auth('api')->user()->id,
        ]);

        return response()->json($procurement);
    }
}
