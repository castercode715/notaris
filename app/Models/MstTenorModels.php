<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstTenorModels extends Model
{
    protected $table = 'mst_tenor';
    protected $fillable = ['tenor','bunga'];
    public $timestamps = false;
}
