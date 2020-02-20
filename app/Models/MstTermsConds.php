<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstTermsConds extends Model
{
    protected $table = 'mst_terms_conds';

    protected $fillable = ['title', 'desc', 'active', 'view', 'sort', 'created_by', 'updated_by', 'deleted_at', 'deleted_by', 'is_deleted'];
}
