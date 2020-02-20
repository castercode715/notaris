<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class MstNews extends Model
{
    protected $table = 'mst_news';
    
    protected $fillable = [
    	'view_count',
    	'active',
    	'created_by',
    	'updated_by',
    	'deleted_date',
    	'deleted_by',
    	'is_deleted'
    ];

    public function getData($code)
    {
        $data = DB::table('mst_news_lang')
                ->where('code',$code)
                ->where('news_id',$this->id)
                ->first();
        return $data;
    }

    public function isComplete()
    {
        $language = MstLanguage::count();

        $news = DB::table('mst_news as h')
                ->join('mst_news_lang as hl','h.id','hl.news_id')
                ->where('h.id', $this->id)
                ->count();

        $result = true;
        if($language != $news)
            $result = false;

        return $result;
    }
}
