<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class SecurityGuide extends Model
{
    protected $table = 'mst_security_guide';
    
    protected $fillable = ['sort','active','created_by','updated_by','deleted_date','deleted_by','is_deleted'];

    public function getData($code)
    {
    	$data = DB::table('mst_security_guide_lang')
    			->where('code',$code)
    			->where('security_guide_id',$this->id)
    			->first();
    	return $data;
    }

    public function isComplete()
    {
        $language = MstLanguage::count();

        $partner = DB::table('mst_security_guide as h')
                ->join('mst_security_guide_lang as hl','h.id','hl.security_guide_id')
                ->where('h.id', $this->id)
                ->count();

        $result = true;
        if($language != $partner)
            $result = false;

        return $result;
    }
}
