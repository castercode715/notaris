<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstPosition extends Model
{
    protected $table = 'mst_position';

    protected $fillable = ['name','page_id','position_code','active','created_by','updated_by','deleted_at','deleted_by','is_deleted'];
}
