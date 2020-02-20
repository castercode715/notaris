<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MstLanguage;
use DB;

class MstGallery extends Model
{
    protected $table = 'mst_gallery';

    protected $fillable = ['sort', 'flag', 'active', 'created_by', 'updated_by', 'deleted_at', 'deleted_by', 'is_deleted'];

    public function getData($code)
    {
    	$data = DB::table('mst_gallery_lang')
    			->where('code',$code)
    			->where('gallery_id',$this->id)
    			->first();
    	return $data;
    }

    public function isComplete()
    {
        $language = MstLanguage::count();

        $gallery = DB::table('mst_gallery as h')
                ->join('mst_gallery_lang as hl','h.id','hl.gallery_id')
                ->where('h.id', $this->id)
                ->count();

        $result = true;
        if($language != $gallery)
            $result = false;

        return $result;
    }
}
