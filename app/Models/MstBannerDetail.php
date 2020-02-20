<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstBannerDetail extends Model
{
	public $timestamps = false;
	
    protected $table = 'mst_banner_detail';

    protected $fillable = ['banner_id','position_id','voucher_id','order'];
}
