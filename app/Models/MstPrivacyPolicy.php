<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class MstPrivacyPolicy extends Model
{
    protected $table = 'mst_privacy_policy';

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
    	$data = DB::table('mst_privacy_policy_lang')
    			->where('code',$code)
    			->where('privacy_policy_id',$this->id)
    			->first();
    	return $data;
    }

    public function isComplete()
    {
        $language = MstLanguage::count();

        $partner = DB::table('mst_privacy_policy as h')
                ->join('mst_privacy_policy_lang as hl','h.id','hl.privacy_policy_id')
                ->where('h.id', $this->id)
                ->count();

        $result = true;
        if($language != $partner)
            $result = false;

        return $result;
    }
}
