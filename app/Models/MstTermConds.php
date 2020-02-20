<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstTermConds extends Model
{
    protected $table = 'mst_terms_conds';

    protected $fillable = ['title', 'desc', 'active', 'created_by', 'updated_by', 'deleted_date', 'deleted_by', 'is_deleted'];
}
