<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kpr;
use App\Models\KprInstallment;
use Yajra\DataTables\DataTables;

class KprInstallmentController extends Controller
{
    public function index()
    {
        return view('kpr.installment.index');
    }

    public function data()
    {
        $model = Kpr::select([
            'app_number',
        ])
        ->join('trc_kpr_installment','')
        ->orderBy('app_number','desc')
        ->get();

        return DataTables::of($model)
        ->make(true);
    }
}
