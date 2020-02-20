<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrcInvBank extends Model
{
	public $timestamps = false;
	
    protected $table = "trc_inv_bank";

    protected $fillable = [
    	'investor_id',
    	'bank_id',
    	'account_holder_name',
		'account_number',
		// 'payment_methode',
		// 'validity_period',
		// 'ccv',
		'active',
		'created_by_investor',
		'created_at_investor',
		'updated_by_emp',
		'updated_at_emp',
		'updated_by_investor',
		'updated_at_investor',
		'deleted_by',
		'deleted_at',
		'is_deleted'
	];
}
