<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\MasterData\Origin;
use Illuminate\Support\Facades\DB;

class OriginController extends Controller
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
            array('title' => 'Origin Code', 'field' => 'origin_code'),
            array('title' => 'Description', 'field' => 'description'),
            array('title' => 'Created By', 'field' => 'created_by_name'),
            array('title' => 'Created At', 'field' => 'created_at'),
            array('title' => 'Modified By', 'field' => 'updated_by_name'),
            array('title' => 'Modified At', 'field' => 'updated_at'),
        ];

        $config = [
            //Title Required
            'title' => 'Origin',
            /**
             * Route Can Be Null
             */
            //Route For Button Add
            'route-add' => 'admin.master_data.origin.create',
            //Route For Button Edit
            'route-edit' => 'admin.master_data.origin.edit',
            //Route For Button Search
            'route-search' => 'admin.master_data.origin.index',
        ];

        $query = Origin::dataTableQuery($searchValue);
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
            array('type' => 'text', 'label' => 'Origin Code', 'name' => 'origin_code', 'place_holder' => 'Origin Code', 'mandatory' => true),
            array('type' => 'text', 'label' => 'Description', 'name' => 'description', 'place_holder' => 'Description', 'mandatory' => true),
        ];
        $config = [
            //Form Title
            'title' => 'Create Origin',
            //Form Action Using Route Name
            'action' => 'admin.master_data.origin.store',
            //Form Method
            'method' => 'POST',
            //Back Button Using Route Name
            'back-button' => 'admin.master_data.origin.index'
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
        DB::select('call insert_origin(?, ?, ?)', array($request->origin_code, $request->description, auth()->user()->id));
        return redirect('admin/master_data/origin');
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
        //Form Generator
        $forms = [
            array('type' => 'text', 'label' => 'Origin Code', 'name' => 'origin_code', 'place_holder' => 'Origin Code', 'mandatory' => true),
            array('type' => 'text', 'label' => 'Description', 'name' => 'description', 'place_holder' => 'Description', 'mandatory' => true),
        ];
        $config = [
            //Form Title
            'title' => 'Update Origin',
            //Form Action Using Route Name
            'action' => 'admin.master_data.origin.update',
            //Form Method
            'method' => 'PATCH',
            //Back Button Using Route Name
            'back-button' => 'admin.master_data.origin.index'
        ];

        $data = Origin::find($id);

        return view('admin.crud.create_or_edit', compact('forms', 'config', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'origin_code' => 'required',
            'description' => 'required'
        ]);

        DB::select('call update_origin(?, ?, ?, ?)', array($request->id, $request->origin_code, $request->description, auth()->user()->id));
        return redirect('admin/master_data/origin');
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
