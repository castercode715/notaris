<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstMember extends Model
{
    protected $table = 'mst_member';

    protected $fillable = ['member','active','created_by','updated_by','deleted_at','deleted_by','is_deleted'];

    public function investors()
    {
    	return $this->hasMany('App\Models\MstInvestor');
    }

    public function members()
    {
    	return $this->hasMany('App\Models\MstMemberLang', 'member_id');
    }
}
