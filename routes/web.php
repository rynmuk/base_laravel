<?php

use Illuminate\Support\Facades\Route;
// All Routes

Auth::routes();
Route::get('/', 'HomeController@index')->name('home'); //Dashboard

Route::group(['prefix'=>'master'], function(){
	
	// User
	Route::resource('sys-user', 'SysUserController', ['as' => 'user'])->middleware(['role','auth']);
	Route::get('table/sys-user', 'SysUserController@dataTable')->name('table.user');

	// Category Project
	Route::resource('category-project', 'SysCategoryController', ['as' => 'category']);
	Route::get('table/category-project', 'SysCategoryController@dataTable')->name('table.category');

	// Project
	Route::resource('project', 'SysProjectController', ['as' => 'project']);
	Route::get('table/project', 'SysProjectController@dataTable')->name('table.project');

	
});

Route::group(['prefix'=>'setting'], function(){
	//Log Activity
	Route::resource('log-activity', 'LogActivityController', ['as' => 'log']);
    Route::get('table/log-activity', 'LogActivityController@dataTable')->name('table.log-activity');

    //Module
	Route::resource('menu', 'SecModuleController', ['as' => 'menu']);
    Route::get('table/menu', 'SecModuleController@dataTable')->name('table.SecModule');

    // Role
    Route::resource('role', 'SecRoleController', ['as' => 'role']);
    Route::get('table/role', 'SecRoleController@dataTable')->name('table.role');

    //Access Role
	Route::resource('access-role', 'SecAccessRoleController', ['as' => 'accessrole']);
	Route::get('table/access-role', 'SecAccessRoleController@dataTable')->name('table.access-role'); 

	Route::get('table/detail-access-role', 'SecAccessRoleController@dataTableDetail')->name('table.detail-access-role');
	Route::post('access-role/add-act', 'SecAccessRoleController@addAct')->name('accessrole.access-role.add-act'); 
	Route::post('access-role/remove-act', 'SecAccessRoleController@removeAct')->name('accessrole.access-role.remove-act');

	
});
