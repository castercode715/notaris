<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DataTables;

class InvestorAssetController extends Controller
{
    public function assetDataTable($id)
    {
        $model = DB::select("
           SELECT
                `tda`.`id`,
                `tda`.`trc_asset_id`,
                `tda`.`transaction_number_detail`,
                `ta`.`transaction_number`,
                `al`.`asset_name`,
                `al`.`asset_id`,
                `tda`.`amount`,
                `ta`.`date`,
                ai.date_start,
                ai.invest_tenor,
                ai.number_interest,
                ( SELECT get_total_interest_by_investment_detail_id ( tda.id ) ) sum_interest,
                ai.date_start,
                ai.date_end,
            CASE
                    WHEN tda.active = 1 THEN
                    'ACTIVE' 
                    WHEN tda.active = 2 THEN
                    'CANCELED' ELSE 'INACTIVE' 
            END status 
            FROM
                `trc_transaction_detail_asset` AS `tda`
                LEFT JOIN `mst_asset_lang` AS `al` ON `tda`.`asset_id` = `al`.`asset_id` 
                AND `al`.`code` = 'IND'
                LEFT JOIN `trc_transaction_asset` AS `ta` ON `tda`.`trc_asset_id` = `ta`.`id` 
                
                left join trc_asset_investor as ai on tda.transaction_number_detail = ai.transaction_number_detail
            WHERE
                `ta`.`investor_id` = ?
            ORDER BY
                `tda`.`id` DESC 
                

        ", [$id]);

        // dd($model);


		return DataTables::of($model)
                ->addColumn('action', function($model){
                    return view('transaction.investor.tab.action_asset', [
                        'model' => $model,
                        'url_show' => route('investor-asset.detail', base64_encode($model->transaction_number)),
                    ]);
                })
				->editColumn('date_start', function($model){
                    return date('d M Y', strtotime($model->date_start));
                })
                ->editColumn('date_end', function($model){
                    return date('d M Y', strtotime($model->date_end));
                })
                ->editColumn('invest_tenor', function($model){
                    return $model->invest_tenor.' Days';
                })
                ->editColumn('number_interest', function($model){
                    return $model->number_interest.'%';
                })
                ->editColumn('amount', function($model){
                    $amount = null;
                    if($model->amount != null || $model->amount != '')
                        $amount = "Rp " . number_format($model->amount,0,',','.');
                    return $amount;
                })
                ->editColumn('status', function($model){
                    $status = '<span class="label label-';
                    if($model->status == 'ACTIVE')
                        $status .= 'success">ACTIVE</span>';
                    elseif($model->status == 'INACTIVE')
                        $status .= 'warning">INACTIVE</span>';
                    elseif($model->status == 'CANCELED')
                        $status .= 'danger">CANCELED</span>';

                    return $status;
                })
                ->addIndexColumn()
                ->rawColumns(['date_start','date_end','amount','status','number_interest','invest_tenor','action'])
                ->make(true);
    }

    public function assetFavDataTable($id)
    {
    	$model = DB::table('mst_asset_favorite as a')
                ->join('mst_asset as b','a.asset_id', '=', 'b.id')
    			->join('mst_asset_lang as al','b.id', '=', 'al.asset_id')
    			->where('a.active','1')
    			->where('a.is_deleted','0')
                ->where('a.investor_id',$id)
    			->where('al.code',"IND")
    			->orderBy('a.id','desc')
    			->select('a.*','al.asset_name','a.comment')
    			->get();

    	return DataTables::of($model)
    			->addIndexColumn()
                ->make(true);
    }

    public function assetFavPane()
    {
    	return view('transaction.investor.tab.fav-asset');
    }

    public function assetPane()
    {
    	return view('transaction.investor.tab.asset');
    }

    public function assetDetail($id)
    {
        $id = base64_decode($id);

        $model = DB::select("select * from v_detail_asset_investor where transaction_number = ?",[$id])[0];
        $model->status  = $this->assetInvestorStatus($model->status);
        $model->date    = date('d M Y H:i', strtotime($model->date)).' WIB';
        $model->total_amount_paid = $model->currency_code.' '.number_format(($model->amount + $model->after_tax),0,',','.');
        $model->amount  = $model->currency_code.' '.number_format($model->amount,0,',','.');
        $model->interest_count = '<strong>'.$model->interest_count.'</strong> <span class="label bg-orange">-'.($model->invest_tenor - $model->interest_count).'</span>';
        $model->invest_tenor = $model->invest_tenor.' Days';
        $model->number_interest = $model->number_interest.'%';
        $model->revenue_start_date = date('d M Y', strtotime($model->revenue_start_date));
        $model->revenue_end_date = date('d M Y', strtotime($model->revenue_end_date));
        $model->total_interest = $model->currency_code.' '.number_format($model->total_interest,0,',','.');
        $model->tax = $model->currency_code.' '.number_format($model->tax,0,',','.');
        $model->rest_paid = $model->currency_code.' '.number_format(($model->after_tax - $model->interest_paid),0,',','.');
        $model->after_tax = $model->currency_code.' '.number_format($model->after_tax,0,',','.');
        $model->daily_interest = $model->currency_code.' '.number_format($model->daily_interest,0,',','.');
        $model->interest_paid = $model->currency_code.' '.number_format($model->interest_paid,0,',','.');

        return view('transaction.investor.tab.asset_detail', compact('model'));
    }

    private function assetInvestorStatus($status)
    {
        $result = '<span class="label ';
        if($status == 'ACTIVE')
            $result .= 'bg-green">ACTIVE</span>';
        else if($status == 'INACTIVE')
            $result .= 'bg-orange">INACTIVE</span>';
        else if($status == 'CANCELED')
            $result .= 'bg-red">CANCELED</span>';
        return $result;
    }
}
