<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlgNews extends Model
{
    protected $table = 'mst_news';
    protected $fillable = ['title','sub_title','desc','image','iframe','active	','created_by','updated_by','deleted_date','deleted_by','is_deleted'];
}
