<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstClass extends Model
{
    protected $table = 'mst_class';

    // protected $fillable = ['name','active','created_by','updated_by','deleted_by','deleted_at','is_deleted'];
    protected $fillable = ['active','created_by','updated_by','deleted_by','deleted_at','is_deleted'];
}
