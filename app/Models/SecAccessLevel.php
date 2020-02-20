<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class SecAccessLevel extends Model
{
    public $timestamps = false;

    protected $table = 'sec_accesslevel';

    protected $fillable = [
    	'module_id',
    	'role_id',
    	'action',
        'created_at',
        'created_by'
    ];
    /*
    action
	C = Create
	R = Read
	U = Update
	D = Delete
	V = View
    */
    public function getModuleChild($id)
    {
    	return DB::table('sec_module')
    			->where('parent_id',$id)
    			->where('active','1')
    			->where('is_deleted','0')
    			// ->select('id','module','parent_id','link')
    			->orderBy('sort')
    			->get();
    }

    public function getCheckedActions($id) // role_id
    {
        $object = DB::table('sec_accesslevel')
                ->where('role_id', $id)
                ->select('module_id','action')
                ->get();
        return $object->toArray();
    }

    public function isActionChecked($module, $action, $checked)
    {
        $result = false;
        foreach($checked as $data)
        {
            if($data->module_id == $module && $data->action == $action)
                $result = true;
        }
        return $result;
    }

    public function isHasChild($id)
    {
        $model = DB::table('sec_module')
                ->where('parent_id',$id)
                ->where('active','1')
                ->where('is_deleted','0')
                ->count();
        return $model;
    }
}
