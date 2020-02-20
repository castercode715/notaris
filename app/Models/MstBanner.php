<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\MstLanguage;

class MstBanner extends Model
{
    protected $table = 'mst_banner';

    protected $fillable = [
    	'link',
    	'active',
    	'created_by',
    	'updated_by',
    	'deleted_at',
    	'deleted_by',
    	'is_deleted'
    ];

    public function getData($code)
    {
        $data = DB::table('mst_banner_lang')
                ->where('code',$code)
                ->where('banner_id',$this->id)
                ->first();
        return $data;
    }

    public function isComplete()
    {
        $language = MstLanguage::count();

        $banner = DB::table('mst_banner as h')
                ->join('mst_banner_lang as hl','h.id','hl.banner_id')
                ->where('h.id', $this->id)
                ->count();

        $result = true;
        if($language != $banner)
            $result = false;

        return $result;
    }
}
