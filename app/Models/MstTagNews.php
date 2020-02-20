<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstTagNews extends Model
{
    protected $table = 'mst_news_tag';
    protected $fillable = ['name','active','created_by','updated_by','deleted_at','deleted_by','is_deleted'];
}
