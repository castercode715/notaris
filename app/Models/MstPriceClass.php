<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstPriceClass extends Model
{
    protected $table = 'mst_class_price';
    protected $fillable = ['class_id','price_start','price_end','interest','active','created_by','updated_by','deleted_date','deleted_by','is_deleted'];
}
