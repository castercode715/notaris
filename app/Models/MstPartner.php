<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MstLanguage;
use DB;

class MstPartner extends Model
{
    protected $table = 'mst_partner';
    protected $fillable = ['sort','active','created_by','updated_by','deleted_date','deleted_by','is_deleted'];
    public function getData($code)
    {
    	$data = DB::table('mst_partner_lang')
    			->where('code',$code)
    			->where('partner_id',$this->id)
    			->first();
    	return $data;
    }

    public function isComplete()
    {
        $language = MstLanguage::count();

        $partner = DB::table('mst_partner as h')
                ->join('mst_partner_lang as hl','h.id','hl.partner_id')
                ->where('h.id', $this->id)
                ->count();

        $result = true;
        if($language != $partner)
            $result = false;

        return $result;
    }
}
