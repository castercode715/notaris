<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstProductCategory extends Model
{
    protected $table = 'mst_product_category';
    protected $fillable = ['product_id','category_id'];

    public $timestamps = false;
}
