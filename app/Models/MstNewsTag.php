<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstNewsTag extends Model
{
    protected $table = 'mst_news_tag';

    protected $fillable = [
    	'active',
    	'created_by',
    	'updated_by',
    	'deleted_date',
    	'deleted_by',
    	'is_deleted'
    ];
}
