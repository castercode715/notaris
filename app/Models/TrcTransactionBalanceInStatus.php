<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrcTransactionBalanceInStatus extends Model
{
    protected $table = "trc_transaction_balance_in_status";

    protected $fillable = [
    	'transaction_balance_in_id',
    	'response',
    	'status',
    	'information',
    	'transfer_receipt',
    	'created_at',
    	'created_by'
    ];

    public function status()
    {
        return [
            'NEW' => ['name'=>'New','label'=>'label label-success'],
            'PENDING' => ['name'=>'Pending','label'=>'label label-warning'],
            'VERIFIED' => ['name'=>'Verified','label'=>'label label-primary'],
            'FAILED' => ['name'=>'Failed','label'=>'label label-danger'],
            'REJECTED' => ['name'=>'Rejected','label'=>'label bg-maroon']
        ];
    }

    public function statusLabel($status)
    {
        $result = '';
        foreach($this->status() as $key => $value)
        {
            if( $status == $key )
            {
                $result = "<span class='".$value['label']."' id='label-tran-status'>".$value['name']."</span>";
                break;
            }
            else
                continue;
        }
        return $result;
    }

    public function method()
    {
        return [
            'MD' => ['name'=>'MD', 'title'=>'Midtrans', 'label'=>'label bg-teal'],
            'PP' => ['name'=>'PP', 'title'=>'PayPal', 'label'=>'label bg-blue'],
            'TM' => ['name'=>'TM', 'title'=>'Transfer Manual', 'label'=>'label bg-orange']
        ];
    }

    public function methodLabel($method)
    {
        $result = '';
        foreach($this->method() as $key => $value)
        {
            if( $method == $key )
            {
                $result = "<span class='".$value['label']."' title='".$value['title']."'>".$value['name']."</span>";
                break;
            }
            else
                continue;
        }
        return $result;
    }
}
