<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceDetailModels extends Model
{
    protected $table = 't_service_details';
    protected $fillable = [
    	't_service_id',
    	'service_id',
    	'quantity',
    	'price',
    	'total',
    	'payment_doc',
    	'surat_pengurusan',
    	'ket_service',
    	'no_akta',
    	'fbm_doc',
    	'minuta_doc',
    	'salinan_doc',
    	'created_by',
    	'updated_by',
    	'deleted_at',
    	'deleted_by',
    	'is_deleted'
    ];
}
