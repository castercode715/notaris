@extends('base.main')
@section('title') Monitoring Balance In @endsection
@section('page_icon') <i class="fa fa-sign-in"></i> @endsection
@section('page_title') Monitoring Balance In @endsection
@section('page_subtitle') list @endsection
@push('css')
<style type="text/css">
.small-box { margin-bottom: 10px; }
</style>
@endpush
@section('content')
    @include('transaction.monitoring-topup.widget')

    <div class="box box-solid">
        <div class="box-header">
            <button class="btn bg-aqua btn-sm" id="btn-reload"><i class="fa fa-refresh"></i> Reload Data</button>
            <button class="btn bg-grey btn-sm" id="btn-clear-filter"><i class="fa fa-filter"></i> Clear Filter</button>

            {!! Form::model($model, [
                'route' => 'monitoring-topup.index',
                'method'=> 'get',
                'id' => 'form_filter'
            ]) !!}
            <div class="row">
                <div class="col-md-4" >
                  <label for="year">Filter date:</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <!-- <input type="text" name="filter_date" class="form-control col-sm-6" id="reservation"> -->
                    <input type="date" id="tgl_awal" name="tgl_awal" style="height: 34px"></input>
                    <input type="date" id="tgl_ahir" name="tgl_ahir" style="height: 34px"></input>
                  </div>
                </div>
                
                <div class="col-md-2">
                  <button type="submit" id="search_data" class="btn btn-primary btn-refresh" style="margin-top: 25px;margin-left: -114px;">
                    <i class="fa fa-search"></i>
                  </button>
                </div>
              </div>
            {!! Form::close() !!}

            <div class="box-tools pull-right">
                {{-- <button class="btn bg-grey btn-sm" id="btn-hint" data-toggle="modal" data-target="#modal-hint"><i class="fa  fa-info-circle"></i></button> --}}
                <div class="margin">
                    <span id="box-last-reload"><i class="fa fa-clock-o"></i> {{ date('d/m/Y H:i:s').' WIB' }} </span>
                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Menu
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="javascript:void(0)" class="btn-topup">Top Up</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="box-body">
          <div class="box-body">
              <table id="datatable" class="table table-hover table-bordered">
                  <thead>
                      <tr>
                          <th>Tran. Number</th>
                          <th>Date</th>
                          <th>Investor</th>
                          <th>Amount</th>
                          <th width="5%">Method</th>
                          <th width="5%">Status</th>
                          <th width="5%">Action</th>
                      </tr>
                  </thead>
              </table>
          </div>
        </div>
    </div>

    @include('transaction.monitoring-topup._form_recheck_reason')
    @include('transaction.monitoring-topup._form_reject_reason')
@endsection

@include('transaction.monitoring-topup.script')