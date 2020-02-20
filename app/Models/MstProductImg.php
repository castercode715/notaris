<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstProductImg extends Model
{
    protected $table = 'mst_product_img';
    protected $fillable = ['product_id','image','featured'];
    public $timestamps = false;
}
