<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysUserLogActivity extends Model
{
    protected $table = 'sys_user_log_activity';

    protected $fillable = [
    	'subject',
    	'url',
    	'method',
    	'ip_address',
    	'agent',
    	'user_id'
    ];
}
