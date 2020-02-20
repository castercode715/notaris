<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MstLanguage;
use DB;

class MstAsset extends Model
{
    public $attr_name, $attr_value;

    protected $table = 'mst_asset';

    protected $fillable = [
        'category_asset_id',
        'terms_conds_id',
        'class_id',
        'owner_name',
        'owner_ktp_number',
        'owner_kk_number',
        'owner_npwp_number',
        'date_available',
        'date_expired',
        'price_market',
        'price_liquidation',
        'price_loan',
        'price_selling',
        'credit_tenor',
        'interest',
        'regencies_id',
        'active',
        'created_by',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'is_deleted'
    ];

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

    public function getAssetBasedOnLanguage($id, $code)
    {
        $data = DB::table('mst_asset_lang')
                ->where('code', $code)
                ->where('asset_id', $id)
                ->first();
        return $data;
    }

    public function isLanguageComplete()
    {
        $language = MstLanguage::count();

        $asset = DB::table('mst_asset as a')
                ->join('mst_asset_lang as al','a.id','al.asset_id')
                ->where('a.id', $this->id)
                ->count();

        $result = true;
        if($language != $asset)
            $result = false;

        return $result;
    }

    public function countAssetByStatus($status)
    {
        $model = DB::select("select count(*) as total from mst_asset where active=".$status." and is_deleted=0")[0]->total;        
        if($model == null)
            $model = 0;
        return $model;
    }

    public function countAssetByStatusInvestasi($status)
    {
        $query = "select count(*) as total from
            (
                select 
                    (select get_sisa_investasi_asset(a.id)) as sisa_investasi, 
                    (select get_sisa_tenor_asset(a.id)) as sisa_tenor
                from 
                    mst_asset a
                where a.is_deleted = 0
            ) tbl ";
        if($status == 1)
            $query .= "where sisa_investasi < 100000 or sisa_tenor < 1";
        else
            $query .= "where sisa_investasi >= 100000 and sisa_tenor >= 1";
        $model = DB::select($query)[0]->total;        
        if($model == null)
            $model = 0;
        return $model;
    }
}
