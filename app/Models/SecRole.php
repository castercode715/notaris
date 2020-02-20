<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class SecRole extends Model
{
    protected $table = "sec_role";

    protected $fillable = ['code', 'role', 'active', 'created_by', 'updated_by', 'deleted_by', 'deleted_at', 'is_deleted'];
}
