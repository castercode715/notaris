<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MstLanguage;
use DB;

class MstHelp extends Model
{
    protected $table = 'mst_help';

    protected $fillable = ['sort', 'active', 'created_by', 'updated_by', 'deleted_at', 'deleted_by', 'is_deleted', 'flag'];

    public function getData($code)
    {
    	$data = DB::table('mst_help_lang')
    			->where('code',$code)
    			->where('help_id',$this->id)
    			->first();
    	return $data;
    }

    public function isComplete()
    {
        $language = MstLanguage::count();

        $help = DB::table('mst_help as h')
                ->join('mst_help_lang as hl','h.id','hl.help_id')
                ->where('h.id', $this->id)
                ->count();

        $result = true;
        if($language != $help)
            $result = false;

        return $result;
    }
}
