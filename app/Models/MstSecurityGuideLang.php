<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstSecurityGuideLang extends Model
{
    public $timestamps = false;
    protected $table = 'mst_security_guide_lang';
    protected $fillable = ['security_guide_id','code','title','description','image'];
}
