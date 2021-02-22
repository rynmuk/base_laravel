<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogActivity;
use Illuminate\Support\Facades\Auth;
use App\Utility;
use DataTables;
use DB;
use Hash;

class LogActivityController extends Controller
{
    const MODULE_NAME = 'Log Activity';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        

        return view('setting.log.index');
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

    public function dataTable()
    {
        $model = LogActivity::query();
        $model->orderBy('id', 'DESC');
        
        return DataTables::of($model)
            
            
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i:s', strtotime($model->created_at))." WIB";
            })
            ->editColumn('description', function($model){
                return substr($model->description, 0, 40)."...";
            })

            ->editColumn('user_id', function($model){
                $uti = new utility();
                return $uti->getUser($model->user_id);
            })
            
            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'create_at', 'user_id','description'])
            ->make(true);
    }
}
