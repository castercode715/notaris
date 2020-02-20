@extends('base.main')
@section('title') Monitoring Order @endsection
@section('page_icon') <i class="fa fa-opencart"></i> @endsection
@section('page_title') Monitoring Order Ecommerce @endsection
@section('page_subtitle') list @endsection

@section('content')
    @include('transaction.order.widget')

    <div class="box box-solid">

        <div class="box-header">
             @include('transaction.order.filter')
        </div>

        <div class="box-body">
            <div class="box-body">
              <table id="datatable" class="table table-hover table-bordered">
                  <thead>
                      <tr>
                          <th width="5%">No</th>
                          <th width="15%">Order. No</th>
                          <th width="15%">Date</th>
                          <th>Investor</th>
                          <th width="10%">Status</th>
                          <th width="2%"></th>
                      </tr>
                  </thead>
              </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
  @include('transaction.order.script')
@endpush
