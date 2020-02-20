<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MCategoryModels extends Model
{
    protected $table = 'm_category';
    protected $fillable = [
        'name',
      	'created_by',
      	'updated_by',
      	'deleted_date',
      	'deleted_by',
      	'is_deleted'
    ];
}
