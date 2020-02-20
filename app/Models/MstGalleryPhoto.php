<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstGalleryPhoto extends Model
{
    protected $table = 'mst_gallery_photo';
    
    protected $fillable = ['gallery_id', 'photo', 'iframe', 'featured', 'active', 'created_by', 'updated_by', 'deleted_at', 'deleted_by', 'is_deleted'];
}
