<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kpr;
use App\Models\KprBooking;
use App\Models\KprAsset;
use App\Models\KprAssetImg;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\DataTables;

class KprBookingController extends Controller
{

    private $list_status = [
        'NEW'   => ['name' => 'NEW', 'label' => 'label label-primary'],
        'SURVEY'   => ['name' => 'SURVEY', 'label' => 'label label-warning'],
        'APPROVED'   => ['name' => 'APPROVED', 'label' => 'label label-success'],
        'REJECTED'   => ['name' => 'REJECTED', 'label' => 'label label-danger'],
        'CANCELED'   => ['name' => 'CANCELED', 'label' => 'label bg-purple'],
    ];

    public function index()
    {
        return view('kpr.booking.index');
    }

    public function survey($id)
    {
        $model = KprBooking::find($id);
        return view('kpr.booking.survey', compact('model'));
    }

    public function data()
    {
        $model = KprBooking::select([
                'trc_kpr_booking.id',
                'trc_kpr_booking.created_at as booking_date',
                'mst_investor.full_name',
                'mst_kpr_asset.code as asset_code',
                'mst_kpr_asset.name as asset',
                'trc_kpr_booking.status'
            ])
            ->join('mst_kpr_asset','trc_kpr_booking.code_kpr','mst_kpr_asset.code')
            ->join('mst_investor','trc_kpr_booking.investor_id','mst_investor.id')
            ->orderBy('trc_kpr_booking.id', 'desc')
            ->get();
        
        return DataTables::of($model)
            ->addColumn('action', function($model){
                return '<a href="'.route('kpr.booking.detail', $model->id).'" class="btn-detail" data-id="'.$model->id.'"><i class="fa fa-search"></i></a>';
            })
            ->editColumn('status', function($model){
                // NEW, SURVEY, REJECTED, APPROVED
                $status = $this->list_status[$model->status];
                return '<span class="'.$status['label'].'">'.$status['name'].'</span>';
            })
            ->editColumn('booking_date', function($model){
                return date('d/m/Y H:i', strtotime($model->booking_date));
            })
            ->rawColumns(['action','status','booking_date'])
            ->make(true);
    }

    protected function countStatus($s)
    {
        return KprBooking::where('status', $s)->count();
    }

    public function widget()
    {
        $new = $this->countStatus('NEW');
        $survey = $this->countStatus('SURVEY');
        $approved = $this->countStatus('APPROVED');
        $rejected = $this->countStatus('REJECTED');
        $canceled = $this->countStatus('CANCELED');

        echo json_encode([
            'new' => $new,
            'survey' => $survey,
            'approved' => $approved,
            'rejected' => $rejected,
            'canceled' => $canceled
        ]);
    }

    public function detail($id)
    {
        $model = KprBooking::select([
            'mst_kpr_asset.code',
            'mst_kpr_asset.name',
            'mst_kpr_asset.location',
            'mst_regencies_lang.name as regency',
            'mst_kpr_asset.price',
            'mst_kpr_asset.tenor',
            'mst_kpr_asset.installment',
            'mst_investor.full_name',
            'trc_kpr_booking.created_at',
            'trc_kpr_booking.status',
            'trc_kpr_booking.surveyor',
            'trc_kpr_booking.surveyor_phone',
            'trc_kpr_booking.survey_start_date',
            'trc_kpr_booking.survey_end_date',
            'trc_kpr_booking.note',
            'trc_kpr_booking.id'
        ])
        ->join('mst_kpr_asset', 'trc_kpr_booking.code_kpr','mst_kpr_asset.code')
        ->join('mst_investor','trc_kpr_booking.investor_id','mst_investor.id')
        ->join('mst_regencies_lang','mst_kpr_asset.location','mst_regencies_lang.regencies_id')
        ->where('trc_kpr_booking.id', $id)
        ->where('mst_regencies_lang.code', 'IND')
        ->first();

        $booking = KprBooking::find($id);
        return view('kpr.booking.detail', compact('model','booking'));
    }

    public function assign(Request $request, $id)
    {
        $request->validate([
            'surveyor' => 'required',
            'phone' => 'required',
            'start_date' => 'required'
        ]);

        $model = KprBooking::find($id);
        $model->update([
            'surveyor' => $request->surveyor,
            'surveyor_phone' => $request->phone,
            'survey_start_date' => date('Y-m-d', strtotime($request->start_date)),
            'updated_by' => Auth::id(),
            'status' => 'SURVEY'
        ]);
    }

    public function approveForm($id)
    {
        return view('kpr.booking.approval', compact('id'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'survey_end_date' => 'required',
            'installment_start_date' => 'required',
            'reason' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $model = KprBooking::find($id);  
            $model->update([
                'status' => 'APPROVED',
                'survey_end_date' => date('Y-m-d', strtotime($request->survey_end_date)),
                'note' => $request->reason,
                'approved_at' => Carbon::now(),
                'approved_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            $asset = KprAsset::find($model->code_kpr);
            $asset->update([
                'status' => 'B',
                'booked_at' => Carbon::now(),
                'booked_by' => Auth::id()
            ]);

            Kpr::create([
                'app_number' => Kpr::appNumber(),
                'code_kpr' => $model->code_kpr,
                'investor_id' => $model->investor_id,
                'price' => $model->price,
                'tenor' => $model->tenor,
                'installment' => $model->installment,
                'booked_date' => $model->created_at,
                'installment_start_date' => date('Y-m-d', strtotime($request->installment_start_date)),
                'installment_end_date' => date('Y-m-d', strtotime($request->installment_start_date.' + '.$model->tenor.' months')),
                'bill_issued_date' => date('d', strtotime($request->installment_start_date)),
                'status' => 'ACTIVE',
                'created_by' => Auth::id()
            ]);

            DB::commit();
        } catch (ValidationException $e) {
            DB::rollBack();
        }
    }

    public function rejectForm($id)
    {
        return view('kpr.booking.reject', compact('id'));
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'survey_end_date' => 'required',
            'reason' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $model = KprBooking::find($id);  
            $model->update([
                'status' => 'REJECTED',
                'survey_end_date' => date('Y-m-d', strtotime($request->survey_end_date)),
                'note' => $request->reason,
                'rejected_at' => Carbon::now(),
                'rejected_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            DB::commit();
        } catch (ValidationException $e) {
            DB::rollBack();
        }
    }

    public function cancel($id)
    {
        return view('kpr.booking.cancel', compact('id'));
    }

    public function cancelling(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required'
        ]);

        $model = KprBooking::find($id);
        $model->update([
            'status' => 'CANCELED',
            'canceled_at' => Carbon::now(),
            'canceled_by' => Auth::id(),
            'note' => $request->reason
        ]);
    }
}
