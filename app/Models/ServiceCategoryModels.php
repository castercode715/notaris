<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategoryModels extends Model
{
    public $timestamps = false;
    protected $table = 'm_service_categories';
    protected $fillable = [
        'service_id',
        'category_id'
    ];
}
