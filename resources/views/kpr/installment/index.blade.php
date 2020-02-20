@extends('base.main')
@section('title') Monitoring Installment @endsection
@section('page_icon') <i class="fa fa-desktop"></i> @endsection
@section('page_title') Monitoring Installment @endsection


@section('content')
    <div class="box box-info">
        <div class="box-body">
            <div class="box-header">
                <button class="btn bg-aqua btn-sm pull-right" id="btn-reload"><i class="fa fa-refresh"></i> Reload Data</button>
                <button class="btn bg-grey btn-sm pull-right" id="btn-clear-filter"><i class="fa fa-filter"></i> Clear Filter</button>

                {!! Form::model($model, [
                    'route' => 'installment.index',
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
                            <input type="date" id="start" name="start" style="height: 34px"></input>
                            <input type="date" id="end" name="end" style="height: 34px"></input>
                        </div>
                    </div>
                
                    <div class="col-md-2">
                        <button type="submit" id="search_data" class="btn bg-aqua btn-refresh" style="margin-top: 17px;margin-left: -100px">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>

                {!! Form::close() !!}

            </div>
            <div class="box-body">
                <table id="datatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>App Number</th>
                            <th>Investor Name</th>
                            <th>Asset KPR</th>
                            <th width="10%">Date Booking</th>
                            <th width="10%">Status</th>
                            <th width="5%"><i class="fa fa-bars"></i></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('kpr.installment.script')
@endpush