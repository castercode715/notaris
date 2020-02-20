<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlgCategoryNews extends Model
{
    protected $table = 'mst_news_category';
    protected $fillable = ['name','active','created_by','updated_by','deleted_date','deleted_by','is_deleted'];
}
