<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdtAsset extends Model
{
    public $attr_name, $attr_value;

    protected $table = 'pdt_asset';

    protected $fillable = ['category_asset_id','asset_name','desc',
    						'owner_name','owner_ktp_number','owner_kk_number',
    						'price_njop','price_market','credit_tenor',
    						'invesment_tenor','terms_conds_id','active',
    						'created_by','updated_by','deleted_date',
    						'deleted_by','is_deleted','images',
    						'file_resume','attr_name','attr_value'];

    public function tenorCreditList()
    {
    	return [
    		3		=> '3',
    		6		=> '6',
    		12		=> '12',
    	];
    }

    public function tenorInvestmentList()
    {
    	return [
    		1		=> '1',
    		3		=> '3',
    		6		=> '6',
    		12		=> '12',
    	];
    }
}
