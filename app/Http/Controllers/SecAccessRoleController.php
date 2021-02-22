<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecAccessRole;
use App\Models\SecModule;
use App\Models\SecRole;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
use DB;
use PDO;
use App\Utility;

class SecAccessRoleController extends Controller
{
    const MODULE_NAME = 'Access Role';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(403,'Unauthorized action');

        return view('setting.access-role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

        $model = new SecAccessRole;
        $role = SecRole::findOrFail($id);
        $modules = SecModule::where('active','1')
                    ->where('is_deleted','0')
                    ->where('parent_id', 0)
                    ->orderBy('sort')
                    ->get();

        $actions = $model->getCheckedActions($id);
        
        return view('setting.access-role.detail', compact(['role','modules','model','actions']));
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

    public function dataTable()
    {
        $model = SecRole::query();
        $model->where('is_deleted','0');
        $model->where('active','1');
        $model->orderBy('id','desc');
        
        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('setting.access-role.action', [
                    'model' => $model,
                    'url_show'=> route('accessrole.access-role.show', $model->id)
                ]);
            })              
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function addAct(Request $request)
    {
        // if(!$this->checkAccess(self::MODULE_NAME, 'C'))
        //     abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'role'                  => 'required',
            'module'                => 'required',
            'action'                => 'required'
        ]);

        if($validate)
        {
            $role = $request->get('role');
            $module = $request->get('module');
            $action = $request->get('action');

            $data = [
                'role_id'           => $role,
                'module_id'         => $module,
                'action'            => $action,
                'created_by'        => Auth::user()->id,
                'created_at'        => date('Y-m-d H:i:s')
            ];

            $model = SecAccessRole::create($data);
            \UserLogActivity::addLog('Add Role #'.$model->role_id.' Module #'.$model->module_id.' Action '.$model->action.' Successfully');
            return $model;
        }
    }

    public function removeAct(Request $request)
    {
        // if(!$this->checkAccess(self::MODULE_NAME, 'D'))
        //     abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'role'  => 'required',
            'module'  => 'required',
            'action'=> 'required'
        ]);

        if($validate)
        {
            $role = $request->get('role');
            $module = $request->get('module');
            $action = $request->get('action');

            $model = DB::table('sec_accessrole')
                    ->where('module_id', $module)
                    ->where('role_id', $role)
                    ->where('action', $action)
                    ->delete();

             \UserLogActivity::addLog('Remove Role #'.$role.' Module #'.$module.' Action '.$action.' Successfully');
            return $model;
        }
    }
}
