<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class MstHelpLang extends Model
{
    protected $table = 'mst_help_lang';

    public $timestamps = false;

    protected $fillable = ['help_id', 'code', 'title', 'description', 'image', 'iframe'];
}
