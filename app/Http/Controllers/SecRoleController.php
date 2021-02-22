<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecRole;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;

class SecRoleController extends Controller
{
    const MODULE_NAME = 'Role';  // ======> Name of log Activity
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('setting.role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new SecRole();
        return view('setting.role.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'code'      => 'required|string|max:5|unique:sec_role,code,1,is_deleted',
            'role'      => 'required|string',
        ]);

        $id = Auth::user()->id;
        $active = 1;

        $roles = [
            'code'          => strtoupper($request->code),
            'role'          => ucwords($request->role),
            'active'        => $active,
            'created_by'    => $id,
            'updated_by'    => $id
        ];

        $model = SecRole::create($roles);

        \UserLogActivity::addLog('Create '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
        return $model;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = SecRole::findOrFail($id);
        $uti = new Utility();
        return view('setting.role.detail', compact(['model', 'uti']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = SecRole::findOrFail($id);
        return view('setting.role.create', compact('model'));
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
        $this->validate($request, [
            'code'          => 'required|string|max:5|unique:sec_role,code,'. $id.',id,is_deleted,0',
            'role'          => 'required|string',
        ]);

        $userId = Auth::user()->id;
        $active = 1;

        $roles = [
            'code'          => strtoupper($request->code),
            'role'          => ucwords($request->role),
            'active'        => $active,
            'updated_by'    => $userId
        ];

        $model = SecRole::findOrFail($id);
        $model->update($roles);
        \UserLogActivity::addLog('Update '.self::MODULE_NAME.' ID #'.$model->id.' Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        DB::update("update sec_role set 
            deleted_at = '".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        \UserLogActivity::addLog('Delete '.self::MODULE_NAME.' ID #'.$id.' Successfully');
    }

    public function dataTable()
    {
        $model = SecRole::query();
        $model->where('is_deleted','<>','1');
                
        return DataTables::of($model)
            
            ->addColumn('action', function($model){
                return view('setting.role.action', [
                    'model' => $model,
                    'url_show'=> route('role.role.show', $model->id),
                    'url_edit'=> route('role.role.edit', $model->id),
                    'url_destroy'=> route('role.role.destroy', $model->id)
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i:s', strtotime($model->created_at));
            })
            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
            
            ->addIndexColumn()
            ->rawColumns(['action', 'checkbox', 'create_at', 'created_by'])
            ->make(true);
    }
}
