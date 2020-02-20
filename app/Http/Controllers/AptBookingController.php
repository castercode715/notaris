<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AptBooking;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AptBookingController extends Controller
{
    protected $status = [
        'new' => ['name' => 'NEW', 'label'=>'label bg-aqua'],
        'interview' => ['name' => 'INTERVIEW', 'label'=>'label label-warning'],
        'approved' => ['name' => 'APPROVED', 'label'=>'label label-success'],
        'rejected' => ['name' => 'REJECTED', 'label'=>'label label-danger'],
        'canceled' => ['name' => 'CANCELED', 'label'=>'label bg-purple'],
        'canceledadm' => ['name' => 'CANCELED BY ADMIN', 'label'=>'label bg-teal'],
    ];

    public function index()
    {
        return view('apartment.booking.index');
    }

    public function data()
    {
        $model = AptBooking::select([
                'trc_apt_booking.id',
                'trc_apt_booking.booking_date',
                'mst_investor.full_name',
                'mst_unit_apt.code_unit',
                'mst_apt_asset.name as apartment',
                'mst_floor_apt.name as floor',
                'mst_unit_apt.name',
                'trc_apt_booking.status'
            ])
            ->join('mst_unit_apt','trc_apt_booking.code_unit','mst_unit_apt.code_unit')
            ->join('mst_floor_apt','mst_unit_apt.code_floor','mst_floor_apt.code_floor')
            ->join('mst_apt_asset','mst_floor_apt.code_apt','mst_apt_asset.code_apt')
            ->join('mst_investor','trc_apt_booking.investor_id','mst_investor.id')
            ->orderBy('trc_apt_booking.id', 'desc')
            ->get();
        return DataTables::of($model)
            ->addColumn('action', function($model){
                return '<a href="'.route('booking.detail', $model->id).'" class="btn-detail" data-id="'.$model->id.'"><i class="fa fa-search"></i></a>';
            })
            ->editColumn('status', function($model){
                // NEW, INTERVIEW, REJECTED, APPROVED, CANCELED, CANCELEDADM
                $s = $this->status[strtolower($model->status)];
                return '<span class="'.$s['label'].'">'.$s['name'].'</span>';
            })
            ->editColumn('booking_date', function($model){
                return date('d/m/Y H:i', strtotime($model->booking_date));
            })
            ->addColumn('unit', function($model){
                return "<strong>".$model->name."</strong> - ".$model->floor."<br>".$model->apartment;
            })
            ->rawColumns(['action','status','booking_date','unit'])
            ->make(true);
    }

    protected function countStatus($s)
    {
        return AptBooking::where('status', $s)->count();
    }

    public function widget()
    {
        $new = $this->countStatus('NEW');
        $interview = $this->countStatus('INTERVIEW');
        $approved = $this->countStatus('APPROVED');
        $rejected = $this->countStatus('REJECTED');
        $canceled = $this->countStatus('CANCELED');
        $canceledadm = $this->countStatus('CANCELEDADM');

        echo json_encode([
            'new' => $new,
            'interview' => $interview,
            'approved' => $approved,
            'rejected' => $rejected,
            'canceled' => $canceled,
            'canceledadm' => $canceledadm
        ]);
    }

    public function detail($id)
    {
        $model = AptBooking::select([
            'trc_apt_booking.id',
            'trc_apt_booking.booking_date',
            'trc_apt_booking.code_unit',
            'trc_apt_booking.price',
            'trc_apt_booking.tenor',
            'trc_apt_booking.installment',
            'trc_apt_booking.maintenance',
            'trc_apt_booking.status',
            'mst_investor.full_name',
            'mst_floor_apt.name as floor',
            'mst_unit_apt.name',
            'mst_apt_asset.name as apartment',
            'mst_regencies_lang.name as regency'
        ])
        ->join('mst_unit_apt','trc_apt_booking.code_unit','mst_unit_apt.code_unit')
        ->join('mst_floor_apt','mst_unit_apt.code_floor','mst_floor_apt.code_floor')
        ->join('mst_apt_asset','mst_floor_apt.code_apt','mst_apt_asset.code_apt')
        ->join('mst_investor','trc_apt_booking.investor_id','mst_investor.id')
        ->join('mst_regencies_lang','mst_apt_asset.location','mst_regencies_lang.regencies_id')
        ->where('mst_regencies_lang.code', 'IND')
        ->where('trc_apt_booking.id', $id)
        ->orderBy('trc_apt_booking.id', 'desc')
        ->first();

        $booking = AptBooking::find($id);
        return view('apartment.booking._i._detail', compact('model','booking'));
    }

    public function formInterview($id)
    {
        return view('apartment.booking._i._interview', compact('id'));
    }

    public function interview(Request $request, $id)
    {
        $request->validate([
            'invite_date' => 'required',
            'place' => 'required'
        ]);
        // update status booking
        $model = AptBooking::find($id);
        $model->update([
            'status'        => 'INTERVIEW',
            'set_up_date'   => date('Y-m-d h:i:s', strtotime($request->invite_date)),
            'place'         => $request->place,
            'interview_at'  => Carbon::now(),
            'interview_by'  => Auth::id()
        ]);
        // send notif email
        $model->emailNotifInterview($id);
        // send notif app
        $model->notifInterview($id);
    }

    public function formApprove($id)
    {
        return view('apartment.booking._i._approve', compact('id'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'note' => 'required'
        ]);
        // update status booking
        $model = AptBooking::find($id);
        $model->update([
            'status'        => 'APPROVED',
            'notes'         => $request->note,
            'approved_at'  => Carbon::now(),
            'approved_by'  => Auth::id()
        ]);
        // send notif email
        $model->emailNotifApprove($id);
        // send notif app
        $model->notifApprove($id);
    }

    public function formReject($id)
    {
        return view('apartment.booking._i._reject', compact('id'));
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'note' => 'required'
        ]);
        // update status booking
        $model = AptBooking::find($id);
        $model->update([
            'status'        => 'REJECTED',
            'notes'         => $request->note,
            'rejected_at'  => Carbon::now(),
            'rejected_by'  => Auth::id()
        ]);
        // send notif email
        $model->emailNotifReject($id);
        // send notif app
        $model->notifReject($id);
    }

    public function formCancel($id)
    {
        return view('apartment.booking._i._cancel', compact('id'));
    }

    public function cancel(Request $request, $id)
    {
        $request->validate([
            'note' => 'required'
        ]);
        // update status booking
        $model = AptBooking::find($id);
        $model->update([
            'status'        => 'CANCELEDADM',
            'notes'         => $request->note,
            'canceled_at'  => Carbon::now(),
            'canceled_by_admin'  => Auth::id()
        ]);
        // send notif email
        $model->emailNotifCancel($id);
        // send notif app
        $model->notifCancel($id);
    }
}
