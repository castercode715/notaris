<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use DB;
use App\Utility;
use DataTables;
use URL;

class SiteController extends Controller
{
    const MODULE_NAME = 'Dashboard';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('site/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function assetReport()
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

        return view('site.asset-best-selling', compact('month'));
    }

    public function bestSellingAsset(Request $request)
    {
        $year   = $request->get('year');
        $month  = $request->get('month');

        $uti = new Utility;
        $rawdata = $uti->bestSellingAsset($year, $month);
        
        $label = [];
        $total = [];
        $ids = [];
        foreach($rawdata as $value)
        {
            $label[] = $value->asset_name;
            $total[] = $value->total;
            $ids[] = $value->id;
        }

        /*$data = [
            'label'                 => 'Asset',
            'fillColor'             => '#3c8dbc',
            'strokeColor'           => '#367fa9',
            'pointColor'            => '#367fa9',
            'pointStrokeColor'      => '#c1c7d1',
            'pointHighlightFill'    => '#fff',
            'pointHighlightStroke'  => 'rgba(220,220,220,1)',
            'data'                  => $total
        ];*/

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
            'ids' => $ids
        ];
        // return [
        //     'datasets' => [$total],
        //     'labels' => $label
        // ];
        // return [
        //     'datasets' => [(object)$total],
        //     'labels' => $label
        // ];
    }

    public function bestSellingAssetInvestor()
    {
        return view('site.investor-pane');
    }

    public function dataTableInvestor($id)
    {
        $model = DB::select("
            select 
                i.id,
                i.full_name,
                ai.amount,
                ai.invest_tenor,
                ai.number_interest
            from 
            trc_asset_investor as ai,
            trc_transaction_asset as ta,
            mst_investor as i
            where 
            ai.trc_asset_id = ta.id
            and ta.investor_id = i.id
            and ai.asset_id = ".$id."
            and lower(ai.status) = 'active'
            order by
                ai.amount desc;
        ");

        return DataTables::of($model)
            // ->addColumn('action', function($model){
            //     return view('master.class.action', [
            //         'model' => $model,
            //         'url_show'=> route('class.show', $model->id),
            //         'url_edit'=> route('class.edit', $model->id),
            //         'url_destroy'=> route('class.destroy', $model->id)
            //     ]);
            // })
            ->editColumn('full_name', function($model){
                return $model->full_name.'&nbsp;<a href="'.URL::to('/').'/transaction/investor/'.base64_encode($model->id).'" target="_blank">
                    <i class="fa fa-external-link"></i>
                </a>';
            })
            ->editColumn('amount', function($model){
                $amount = null;
                if($model->amount != null || $model->amount != '')
                    $amount = "Rp " . number_format($model->amount,0,',','.');
                return $amount;
            })
            ->addIndexColumn()
            ->rawColumns(['amount','full_name'])
            ->make(true);
    }
}
