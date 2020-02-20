@extends('base.main')
@section('title') Monitoring Withdraw @endsection
@section('page_icon') <i class="fa fa-sign-out"></i> @endsection
@section('page_title') Monitoring Withdraw @endsection
@section('page_subtitle') list @endsection


@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-green">
            <div class="inner">
              <h3 id="success-value">{{ $success }}</h3>

              <p><b>Success</b></p>
            </div>
            <div class="icon">
              <i class="fa fa-bell"></i>
            </div>
            <a href="javascript:void(0)" class="small-box-footer" id="success">
              Show data <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
              <h3 id="process-value">{{ $process }}</h3>

              <p><b>Process</b></p>
            </div>
            <div class="icon">
              <i class="fa fa-tasks"></i>
            </div>
            <a href="javascript:void(0)" class="small-box-footer" id="process">
              Show data <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3 id="verified-value">{{ $verified }}</h3>

              <p><b>Verified</b></p>
            </div>
            <div class="icon">
              <i class="fa fa-check"></i>
            </div>
            <a href="javascript:void(0)" class="small-box-footer" id="verified">
              Show data <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3 id="rejected-value">{{ $rejected }}</h3>

              <p><b>Rejected</b></p>
            </div>
            <div class="icon">
              <i class="fa fa-ban"></i>
            </div>
            <a href="javascript:void(0)" class="small-box-footer" id="rejected">
              Show data <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
    </div>

    <div class="box box-solid">
        <div class="box-header">


            <button class="btn bg-aqua btn-sm pull-right" id="btn-reload"><i class="fa fa-refresh"></i> Reload Data</button>
            <button class="btn bg-grey btn-sm pull-right" id="btn-clear-filter"><i class="fa fa-filter"></i> Clear Filter</button>
            
            {!! Form::model($model, [
              'route' => 'monitoring-cashout.index',
              'method'=> 'get',
              'id' => 'form_filter'
            ]) !!}

              <div class="row">
                <div class="col-md-4" style="margin-top: -8px;">
                  <label for="year">Filter date:</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <!-- <input type="text" name="filter_date" class="form-control col-sm-6" id="reservation"> -->
                    <input type="date" id="tgl_awal" name="tgl_awal" style="height: 34px;"></input>
                    <input type="date" id="tgl_ahir" name="tgl_ahir" style="height: 34px;"></input>
                  </div>
                </div>
                
                <div class="col-md-2">
                  <button type="submit" id="search_data" class="btn btn-primary btn-refresh" style="margin-top: 17px;margin-left: -114px;">
                    <i class="fa fa-search"></i>
                  </button>
                </div>
              </div>

            {!! Form::close() !!}
          
        </div>
        <div class="box-body">
            <div class="box-body">
              <table id="datatable" class="table table-hover table-bordered">
                  <thead>
                      <tr>
                          <th width="50px">No</th>
                          <th>Tran. No</th>
                          <th>Date</th>
                          <th width="30px">Age</th>
                          <th>Investor</th>
                          <th>Amount</th>
                          <th>Status</th>
                          <th width="50px">Action</th>
                      </tr>
                  </thead>
              </table>
            </div>
        </div>
    </div>
  @include('transaction.monitoring-topup-out._form_reject_reason')
@endsection

@include('transaction.monitoring-topup-out.script')