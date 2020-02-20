<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstMemberLang extends Model
{
    public $timestamps = false;
    protected $table = 'mst_member_lang';
    protected $fillable = ['member_id','code','description','image'];

    public function member()
    {
    	return $this->belongsTo('App\Models\MstMember', 'member_id');
    }
}
