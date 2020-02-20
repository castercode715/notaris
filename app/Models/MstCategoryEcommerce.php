<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class MstCategoryEcommerce extends Model
{
    protected $table = 'mst_category_ecommerce';
    protected $fillable = ['name','image','parent_id','created_by','updated_by','deleted_date','deleted_by','is_deleted'];

    public function childs($id) {
        $result = DB::table('mst_category_ecommerce as a')
        			->where('a.parent_id', $id)
        			->where('a.is_deleted', 0)
        			->get();

       	return $result;
    }

    public function isAllowedParent($id) {
        $result = DB::table('mst_category_ecommerce as a')
        			->where('a.id', $id)
        			->where('a.is_deleted', '=', 3)
        			->count();

       	return $result;
    }

    public function isAllowedDelete($id) {
        $result = DB::table('mst_category_ecommerce as a')
        			->where('a.parent_id', '=', $id)
        			->count();

       	return $result;
    }
}
