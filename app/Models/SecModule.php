<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecModule extends Model
{
    protected $table = 'sec_module';
    protected $fillable = ['module','link','icon','sort','parent_id','active','created_by','updated_by','is_deleted'];
    // public function parent()
    // {
    // 	return $this->belongsTo('App\Models\SecModule');
    // }

    public function getParent()
    {
    	$model = SecModule::findOrFail($this->parent_id);
    	return $model->module;
    }
}
