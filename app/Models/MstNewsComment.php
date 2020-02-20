<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class MstNewsComment extends Model
{
    protected $table = 'mst_news_comment';

    protected $fillable = [
    	'news_id',
    	'investor_id',
    	'parent',
    	'comment',
    	'is_visible',
    	'is_deleted',
    	'active'
    ];

    public function count($id)
    {
        return DB::table('mst_news_comment')
                ->where('parent',$id)
                ->count();
    }
}
