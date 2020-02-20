<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstNewsTagDetail extends Model
{
    protected $table = 'mst_news_tag_detail';
    
    protected $fillable = [
    	'news_id',
    	'tag_id',
    	'active',
    	'created_by',
    	'updated_by',
    	'deleted_date',
    	'deleted_by',
    	'is_deleted'
    ];
}
