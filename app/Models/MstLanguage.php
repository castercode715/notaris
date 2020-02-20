<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstLanguage extends Model
{
    protected $table = 'mst_language';

    protected $fillable = [
    	'code',
    	'language'
    ];

    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false; 
    public $timestamps = false;
}
