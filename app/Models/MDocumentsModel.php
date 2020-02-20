<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MDocumentsModel extends Model
{
    protected $table = 'm_documents';
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
