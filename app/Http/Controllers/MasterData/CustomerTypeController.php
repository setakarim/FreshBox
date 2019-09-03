<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\MasterData\CustomerType;
use Illuminate\Support\Facades\DB;

class CustomerTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchValue = $request->input('search');

        $columns = [
            array('title' => 'Nama', 'field' => 'name'),
            array('title' => 'Description', 'field' => 'description'),
            array('title' => 'Created By', 'field' => 'created_by_name'),
            array('title' => 'Created At', 'field' => 'created_at'),
            array('title' => 'Modified By', 'field' => 'updated_by_name'),
            array('title' => 'Modified At', 'field' => 'updated_at'),
        ];

        $config = [
            //Title Required
            'title' => 'Customer Type',
            /**
             * Route Can Be Null
             */
            //Route For Button Add
            'route-add' => 'admin.master_data.customer_type.create',
            //Route For Button Edit
            'route-edit' => 'testing.edit',
            //Route For Button Search
            'route-search' => 'admin.master_data.customer_type.index',
        ];

        $query = CustomerType::dataTableQuery($searchValue);
        $data = $query->paginate(10);

        return view('admin.crud.index', compact('columns', 'data', 'config'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Form Generator
        $forms = [
            array('type' => 'text', 'label' => 'Customer Type', 'name' => 'name', 'place_holder' => 'Customer Type'),
            array('type' => 'text', 'label' => 'Description', 'name' => 'description', 'place_holder' => 'Description'),
        ];
        $config = [
            //Form Title
            'title' => 'Create Customer Type',
            //Form Action Using Route Name
            'action' => 'admin.master_data.customer_type.store',
            //Form Method
            'method' => 'POST',
            //Back Button Using Route Name
            'back-button' => 'admin.master_data.customer_type.index'
        ];

        return view('admin.crud.create_or_edit', compact('forms', 'config'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::select('call insert_customer_type(?, ?, ?)', array($request->name, $request->description, auth()->user()->id));
        return redirect('admin/master_data/customer_type');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
