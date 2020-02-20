<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstTermsCondsLang extends Model
{
	public $timestamps = false;
    protected $table = 'mst_terms_conds_lang';
    protected $fillable = ['terms_conds_id','code','title','description'];
}
