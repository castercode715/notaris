<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstAttributeEcommerce extends Model
{
    protected $table = 'mst_attribute_ecommerce';
    protected $fillable = ['name','active','created_by','updated_by','deleted_date','deleted_by','is_deleted'];
}
