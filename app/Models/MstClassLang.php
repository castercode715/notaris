<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstClassLang extends Model
{
    protected $table = 'mst_class_lang';

    public $timestamps = false;

    protected $fillable = ['class_id','code','description','image'];
}
