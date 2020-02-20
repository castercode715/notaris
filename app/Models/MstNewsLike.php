<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstNewsLike extends Model
{
    protected $table = 'mst_news_like';

    protected $fillable = [
    	'news_id',
    	'investor_id',
    	'created_at'
    ];

    public $timestamps = false;
}
