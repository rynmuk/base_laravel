<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysUserModel extends Model
{
    protected $table = 'users';
    protected $fillable = [
    	'name',
    	'address',
    	'phone_number',
    	'email',
    	'email_verified_at',
    	'password',
    	'remember_token',
    	'created_by',
    	'updated_by',
    	'deleted_date',
    	'deleted_by',
    	'is_deleted',
        'role',
    	'current_sign_in_at',
    	'last_sign_in_at',
    ];
}
