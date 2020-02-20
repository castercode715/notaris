<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstDenahModels extends Model
{
    protected $primaryKey = 'code_floor';
    protected $keyType = 'string'; 
    protected $increments = false;
    public $timestamps = false;

    protected $table = 'mst_floor_apt';
    protected $fillable = ['code_floor','name','denah','code_apt'];
}
