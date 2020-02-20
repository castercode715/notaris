<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceDocumentModels extends Model
{
    public $timestamps = false;
    protected $table = 'm_service_documents';
    protected $fillable = [
        'service_id',
        'document_id'
    ];
}
