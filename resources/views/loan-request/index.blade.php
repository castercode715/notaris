@extends('base.main')
@section('title') Loan Request @endsection
@section('page_icon') <i class="fa fa-sign-out"></i> @endsection
@section('page_title') Loan Request @endsection
@section('page_subtitle') list @endsection


@section('content')
    <div class="box box-solid">
        <div class="box-header">
            <button class="btn bg-aqua btn-sm" id="btn-reload"><i class="fa fa-refresh"></i> Reload Data</button>
            <button class="btn bg-grey btn-sm" id="btn-clear-filter"><i class="fa fa-filter"></i> Clear Filter</button>
            <div class="box-tools pull-right">
                <button class="btn bg-grey btn-sm" id="btn-hint" data-toggle="modal" data-target="#modal-hint"><i class="fa  fa-info-circle"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="box-body">
              <table id="datatable" class="table table-hover table-bordered">
                  <thead>
                      <tr>
                          <th width="50px">No</th>
                          <th>Date</th>
                          <th>Name</th>
                          <th>HP</th>
                          <th>Email</th>
                          <th>Status</th>
                          <th width="50px">Action</th>
                      </tr>
                  </thead>
              </table>
            </div>
        </div>
    </div>
@endsection

@include('loan-request.script')