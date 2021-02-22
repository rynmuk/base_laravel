<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SysUserModel;
use Illuminate\Support\Facades\Auth;
use App\Utility;
use DataTables;
use DB;
use Hash;

class SysUserController extends Controller
{
    const MODULE_NAME = 'User';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(403,'Unauthorized action');
        
        return view('master.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(403,'Unauthorized action');

        $model = new SysUserModel();
        return view('master.user.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(403,'Unauthorized action');

        $this->validate($request,[
            'name'                  => 'required|string',
            'email'                 => 'required|string|unique:users,email,1,is_deleted',
            'phone_number'          => 'required|string',
            'address'               => 'required|string',
            'password'              => 'required|required_with:password_confirmation|string|confirmed',
            'role'                  => 'required|string',

        ]);

        $data = [
            'name'                  => ucwords($request->name),
            'email'                 => $request->email,
            'phone_number'          => $request->phone_number,
            'address'               => $request->address,
            'password'              => Hash::make($request->password),
            'role'                  => $request->role,
            'created_by'            => Auth::user()->id,
            'updated_by'            => Auth::user()->id
        ];
        
        $model = SysUserModel::create($data);

        // Add to log
        \UserLogActivity::addLog('Create Module '. self::MODULE_NAME.' #ID '.$model->id." Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(403,'Unauthorized action');

        $model = SysUserModel::findOrFail(base64_decode($id));
        $uti = new Utility();
        return view('master.user.detail', compact(['model','uti']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(403,'Unauthorized action');

        $model = SysUserModel::findOrFail(base64_decode($id));
        return view('master.user.create', compact('model'));
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
        if(!$this->checkAccess(self::MODULE_NAME, 'U'))
            abort(403,'Unauthorized action');

        $this->validate($request,[
            'name'                  => 'required|string',
            'email'                 => 'required|string|unique:users,email,'.$id.',id,is_deleted,0',
            'phone_number'          => 'required|string',
            'address'               => 'required|string',
            'password'              => 'nullable|required_with:password_confirmation|string|confirmed',
            'role'                  => 'required|string',
        ]);

        $model = SysUserModel::findOrFail($id);

        if ($request->password == '') {
            
            $data = [
                'name'                  => ucwords($request->name),
                'email'                 => $request->email,
                'phone_number'          => $request->phone_number,
                'address'               => $request->address,
                // 'password'              => Hash::make($request->password),
                'role'                  => $request->role,
                'created_by'            => Auth::user()->id,
                'updated_by'            => Auth::user()->id
            ];
        }else{

            $data = [
                'name'                  => ucwords($request->name),
                'email'                 => $request->email,
                'phone_number'          => $request->phone_number,
                'address'               => $request->address,
                'password'              => Hash::make($request->password),
                'role'                  => $request->role,
                'created_by'            => Auth::user()->id,
                'updated_by'            => Auth::user()->id
            ];
        }

        $model->update($data);
        \UserLogActivity::addLog('Update Module '. self::MODULE_NAME.' #ID '.$id." Successfully");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(403,'Unauthorized action');

        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        DB::update("update users set 
            deleted_at='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".base64_decode($id)."");

        \UserLogActivity::addLog('Delete Module '. self::MODULE_NAME.' #ID '.base64_decode($id)." Successfully");
    }

    public function dataTable()
    {
        $model = SysUserModel::query();
        $model->where('is_deleted','<>','1');
        
        return DataTables::of($model)
            
            ->addColumn('action', function($model){
                return view('master.user.action', [
                    'model' => $model,
                    'url_show'=> route('user.sys-user.show', base64_encode($model->id)),
                    'url_edit'=> route('user.sys-user.edit', base64_encode($model->id)),
                    'url_destroy'=> route('user.sys-user.destroy', base64_encode($model->id))
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i:s', strtotime($model->created_at))." WIB";
            })
            ->editColumn('description', function($model){
                return substr($model->description, 0, 40)."...";
            })

            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
            
            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'create_at', 'created_by','description'])
            ->make(true);
    }
}
