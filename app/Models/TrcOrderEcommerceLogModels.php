<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrcOrderEcommerceLogModels extends Model
{
    public $timestamps = false;
    protected $table = 'trc_order_ecommerce_log';
    protected $fillable = [
    	'order_id', 
    	'note', 
    	'status', 
    	'date', 
    	'by',
    	'flag',
    ];
}
