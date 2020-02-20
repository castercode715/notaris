<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstBank extends Model
{
    protected $table = 'mst_bank';

    protected $fillable = ['name','card_type','countries_id','image_logo','active','created_by','updated_by','deleted_by','deleted_at','is_deleted'];

    public function cardTypeList()
    {
    	return [
    		'C'	=> 'Credit',
    		'D'	=> 'Debit'
    	];
    }

    public function getCardType()
    {
    	$card = '';
    	foreach($this->cardTypeList() as $key => $value)
    	{
    		if($this->card_type == $key)
    		{
    			$card = $value;
    			break;
    		}
    	}
    	return $card;
    }
}
