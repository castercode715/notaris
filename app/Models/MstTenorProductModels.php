<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstTenorProductModels extends Model
{
    protected $table = 'mst_tenor_product';
    protected $fillable = ['tenor_id','product_id'];
    
    public $timestamps = false;
}
