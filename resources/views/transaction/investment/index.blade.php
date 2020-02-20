@extends('base.main')
@section('title') Monitoring Investment @endsection
@section('page_icon') <i class="fa fa-"></i> @endsection
@section('page_title') Monitoring Investment @endsection
@section('page_subtitle') list @endsection


@section('content')
    <div class="row">
        <div class="col-lg-6 col-xs-6">
          <div class="small-box bg-red">
            <div class="inner">
              <h3 id="success-value">
                    @foreach($cutoff as $cut)
                        {{ $cut->cut_off }}
                    @endforeach
              </h3>

              <p><b>Hasn't been Cut Off</b></p>
            </div>
            <div class="icon">
              <i class="fa fa-cut"></i>
            </div>
            <a href="?filter=N" class="small-box-footer" id="success">
              Show data <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-6 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3 id="process-value">
                    @foreach($cancel as $cancelled)
                        {{ $cancelled->cancelled }}
                    @endforeach
              </h3>

              <p><b>Transaction Cancelled</b></p>
            </div>
            <div class="icon">
              <i class="fa fa-close"></i>
            </div>
            <a href="?filter=Y" class="small-box-footer" id="process">
              Show data <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        
        <!-- ./col -->
        
        <!-- ./col -->
    </div>

    <div class="box box-solid">
        <div class="box-header">


            <button class="btn bg-aqua btn-sm pull-right" id="btn-reload"><i class="fa fa-refresh"></i> Reload Data</button>
            <button class="btn bg-grey btn-sm pull-right" id="btn-clear-filter"><i class="fa fa-filter"></i> Clear Filter</button>
            
            {!! Form::model($model, [
                'route' => 'investment.index',
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
                    <input type="date" id="start" name="start" style="height: 34px"></input>
                    <input type="date" id="end" name="end" style="height: 34px"></input>
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
                          <th width="5%">No</th>
                          <th>Tran. No</th>
                          <th>Date</th>
                          <th>Investor</th>
                          <th>Amount</th>
                          <th width="10%">Cut Off</th>
                          <th>Total Asset</th>
                          <th width="5%">Action</th>
                      </tr>
                  </thead>
              </table>
            </div>
        </div>
    </div>

@endsection

@include('transaction.investment.script')