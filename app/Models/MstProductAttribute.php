<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstProductAttribute extends Model
{
    public $timestamps = false;

    protected $table = 'mst_product_attribute';
    protected $fillable = [
    	'product_id',
    	'attribute_ecommerce_id',
    	'value'
    ];

}
