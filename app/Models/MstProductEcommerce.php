<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class MstProductEcommerce extends Model
{
    protected $table = 'mst_product_ecommerce';
    protected $fillable = [
    	'name',
    	'price',
    	'discount',
    	'status',
    	'desc',
    	'term_conds',
    	'created_by',
    	'updated_by',
    	'deleted_date',
    	'deleted_by',
    	'is_deleted'
    ];

    public function getValueAttributeProduct($id, $product_id)
    {
        $query = DB::table('mst_product_attribute as a')
                    ->select('a.value')
                    ->where('a.attribute_ecommerce_id', $id)
                    ->where('a.product_id', $product_id)
                    ->get();

        $values = '';
        $no = 0;
        $max = count($query);
        $maxcomma = $max--;

        foreach ($query as $key => $value) {

            $values .= $value->value;
            if ($no < $max) {
                $values .= ', ';
            }

            $no++;
            // $maxcomma--;
        }

        return $values;
        
    }

    public function getNameProduct($id)
    {
        $result = DB::table('mst_product_ecommerce as a')
                    ->where('id', $id)
                    ->get();

        $data = '';
        foreach ($result as $key) {
            $data .= $key->name;
        }

        return $data;
    }

    public function getProductAttr($id)
    {
        $query = DB::table('mst_product_attribute as a')
                    ->join('mst_attribute_ecommerce as b','b.id','=','a.attribute_ecommerce_id')
                    ->select('b.name', 'a.value')
                    ->where('a.id', $id)
                    ->get();

        return $query;
    }

    public function getchildCategory($id)
    {
        $query = DB::table('mst_category_ecommerce as a')
                ->where('a.is_deleted', 0)
                ->where('a.parent_id', $id)
                ->get();

        return $query;
    }


}
