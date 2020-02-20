<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstPrivacyPolicyLang extends Model
{
    protected $table = 'mst_privacy_policy_lang';

    protected $fillable = [
    	'privacy_policy_id',
    	'code',
    	'title',
    	'description',
    	'image'
    ];

    public $timestamps = false;
}
