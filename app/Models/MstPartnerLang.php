<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstPartnerLang extends Model
{
    protected $table = 'mst_partner_lang';

    public $timestamps = false;

    protected $fillable = ['partner_id','code','title','description','image','iframe'];
}
