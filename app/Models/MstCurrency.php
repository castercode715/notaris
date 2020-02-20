<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstCurrency extends Model
{
    protected $table = 'mst_currency';

    protected $fillable = [
    	'code',
    	'symbol',
    	'currency'
    ];

    public $timestamps = false;

    public $primaryKey = 'code';

    public $incrementing = false;

    protected $keyType = 'string';
}
