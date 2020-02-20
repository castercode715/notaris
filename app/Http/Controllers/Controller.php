<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Utility;
use Illuminate\Support\Facades\Auth;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $menus = [];

    public function getUri()
    {
    	$request = new Request;
    	echo $request->is('master/member') ? 'A' : 'B';
    }

    public function checkAccess($modul, $action)
    {
    	$role = Auth::user()->role_id;
    	// get id module
    	$module = DB::table('sec_module')
    			->where('module', $modul)
                ->where('is_deleted', '0')
                ->where('active', '1')
    			->select('id')
    			->first();
    	// check access
    	$check = DB::table('sec_accesslevel')
    			->where('role_id', $role)
    			->where('module_id', $module->id)
    			->where('action', $action)
    			->count();
    	$result = false;
    	if($check > 0)
    		$result = true;

    	return $result;
    }

    public function isAllowedToUpdate($childTables = array(), $fk_ids = array(), $pk_id)
    {
        $uti = new Utility();
        return $uti->isAllowedToUpdate($childTables, $fk_ids, $pk_id);
    }	

    public function isAllowedToUpdate2($childTables = array(), $fk_ids = array(), $pk_id)
    {
        $uti = new Utility();
        return $uti->isAllowedToUpdate2($childTables, $fk_ids, $pk_id);
    }   
}
