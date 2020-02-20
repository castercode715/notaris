<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstPage extends Model
{
    protected $table = 'mst_page';

    protected $fillable = ['description','active','created_by','updated_by','deleted_at','deleted_by','is_deleted'];
}
