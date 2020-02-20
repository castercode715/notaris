<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MServicesModels extends Model
{
    protected $table = 'm_services';
    protected $fillable = [
        'name',
        'sort',
      	'created_by',
      	'updated_by',
      	'deleted_date',
      	'deleted_by',
      	'is_deleted'
    ];
}
