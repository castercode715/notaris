<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TrcOrderEcommerce extends Model
{
	public $timestamps = false;
    protected $table = 'trc_order_ecommerce';
    protected $fillable = [
    	'investor_id', 
    	'expedition_name', 
    	'no_order', 
    	'tenor', 
    	'bunga', 
    	'total_price', 
    	'installment', 
    	'installment_start_date', 
    	'installment_end_date', 
    	'date_transaction', 
    	'date_received', 
    	'no_resi', 
    	'deleted_at', 
    	'deleted_by',
    	'assign_at',
    	'assign_by',
    	'process_at',
    	'process_by',
    	'received_by_admin',
    	'is_deleted'
    ];


    public function getTenor($id)
    {
        $query = DB::table('mst_tenor_product as a')
                    ->join('mst_tenor as b','b.id','=','a.tenor_id')
                    ->select('b.tenor', 'b.bunga')
                    ->where('a.id', $id)
                    ->get();

        $result = '';
        foreach ($query as $key) {
            $result .= $key->tenor;
        }

        return $result;
    }

    public function getBunga($id)
    {
        $query = DB::table('mst_tenor_product as a')
                    ->join('mst_tenor as b','b.id','=','a.tenor_id')
                    ->select('b.tenor', 'b.bunga')
                    ->where('a.id', $id)
                    ->get();

        $result = '';
        foreach ($query as $key) {
            $result .= $key->bunga;
        }

        return $result;
    }

    public function getProduct($id)
    {
        $query = DB::table('mst_product_ecommerce as a')
                    ->where('a.id', $id)
                    ->select('a.name')
                    ->get();

        $result = '';
        foreach ($query as $key) {
            $result .= $key->name;
        }

        return $result;
    }

    public function getAttributeEcommerce($id)
    {
        $query = DB::table('trc_order_detail_atribute_ecommerce as a')
                    ->join('trc_order_details_ecommerce as b', 'b.id', '=', 'a.order_detail_id')
                    ->where('a.order_detail_id', $id)
                    ->get();

        $result = '';
        foreach ($query as $key) {
            $result .= $key->product_atrribute_id;
        }

        return $result;
    }

    public function getAttr($id)
    {
        $query = DB::table('trc_order_detail_atribute_ecommerce as a')
                    ->join('mst_product_attribute as b', 'b.id', '=', 'a.product_atrribute_id')
                    ->join('mst_attribute_ecommerce as c', 'c.id', '=', 'b.attribute_ecommerce_id')
                    ->where('a.order_detail_id', $id)
                    ->get();

        $result = '';

        $no = 0;
        $max = count($query);
        $maxcomma = $max--;

        foreach ($query as $key) {
            $result .= $key->name.": ".$key->value."";

            if ($no < $max) {
                $result .= ', ';
            }

             $no++;
        }

        return $result;
    }

    public function countStatus($status)
    {
        $result = DB::table('trc_order_ecommerce_log as a')
                ->where('a.status', $status)
                ->where('a.flag', 1)
                ->count();

        return $result;
    }

    

}
