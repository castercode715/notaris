<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Utility;
use DataTables;
use URL;

class DashboardController extends Controller
{
    public function dashboardHighestInvestment()
    {
        $month = [
            '01'    => 'January',
            '02'    => 'February',
            '03'    => 'March',
            '04'    => 'April',
            '05'    => 'May',
            '06'    => 'June',
            '07'    => 'July',
            '08'    => 'August',
            '09'    => 'September',
            '10'    => 'October',
            '11'    => 'November',
            '12'    => 'December',
        ];
    	return view('dashboard.highest_investment.index', compact('month'));
    }

    public function dataHighestInvestment(Request $request)
    {
    	$year   = $request->get('year');
        $month  = $request->get('month');

        $uti = new Utility;
        $rawdata = $uti->highestInvestment($year, $month);

        $label = [];
        $total = [];
        $ids = [];
        foreach($rawdata as $value)
        {
            $label[] = $value->full_name;
            $total[] = $value->amount;
            $ids[] = $value->id;
        }

        $data = [
            'label'                 => 'Investor',
            'backgroundColor'       => 'rgba(54, 162, 235, 0.6)',
            'borderColor'           => 'rgba(54, 162, 235, 1)',
            'borderWidth'           => 1,
            'data'                  => $total
        ];

        return [
            'datasets' => [(object)$data],
            'labels' => $label,
            'ids' => $ids,
        ];
    }

    public function assetHighestInvestment()
    {
    	return view('dashboard.highest_investment.investor-asset');
    }

    public function tableHighestInvestmentAsset($id)
    {
    	$model = DB::select("
    		select 
				a.id,
				al.asset_name,
				ai.amount,
				ai.number_interest,
				ai.invest_tenor,
				ai.status
			from 
				trc_asset_investor as ai
			join trc_transaction_asset as ta on ai.trc_asset_id = ta.id 
			join mst_asset as a on ai.asset_id = a.id 
            join mst_asset_lang as al on a.id = al.asset_id
			where 
				ta.investor_id = ".$id."
            and al.code = 'IND'
    	");

    	return DataTables::of($model)
    		->editColumn('amount', function($model){
                $amount = null;
                if($model->amount != null || $model->amount != '')
                    $amount = "Rp " . number_format($model->amount,0,',','.');
                return $amount;
            })
    		->editColumn('status', function($model){
    			$status = "<span class='label label-success'>Active</span>";
    			if(strtolower($model->status) != 'active')
    				$status = "<span class='label label-warning'>Inactive</span>";
    			return $status;
            })
    		->addIndexColumn()
            ->rawColumns(['amount','status'])
            ->make(true);
    }
}
