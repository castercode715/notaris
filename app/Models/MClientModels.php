<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MClientModels extends Model
{
    protected $table = 'm_client';
    protected $fillable = [
        'notaris_id',
        'name',
        'address',
        'district_id',
        'kode_pos',
        'telephone_number',
        'handphone_number',
        'email',
        'no_ktp',
        'no_npwp',
        'no_kk',
        'ktp_pasangan_no',
        'ktp_doc',
        'npwp_doc',
        'kk_doc',
        'ktp_pasangan_doc',
        'buku_nikah_doc',
        'status',
        'jaminan',
        'jaminan_doc',
        'client_flag',
      	'created_by',
      	'updated_by',
      	'deleted_date',
      	'deleted_by',
      	'is_deleted'
    ];

    
}
