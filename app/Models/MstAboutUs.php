<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class MstAboutUs extends Model
{
    protected $table = 'mst_about_us';
    protected $fillable = [
    	'sort',
    	'active',
    	'created_by',
    	'updated_by',
    	'deleted_at',
    	'deleted_by',
    	'is_deleted'
    ];

    public function getData($code)
    {
    	$data = DB::table('mst_about_us_lang')
    			->where('code',$code)
    			->where('about_us_id',$this->id)
    			->first();
    	return $data;
    }

    public function isComplete()
    {
        $language = MstLanguage::count();

        $partner = DB::table('mst_about_us as h')
                ->join('mst_about_us_lang as hl','h.id','hl.about_us_id')
                ->where('h.id', $this->id)
                ->count();

        $result = true;
        if($language != $partner)
            $result = false;

        return $result;
    }
}
