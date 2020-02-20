@extends('base.main')
@section('title') Investor @endsection
@section('page_icon') <i class="fa fa-users"></i> @endsection
@section('page_title') Investor @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('investor.create') }}" class="btn btn-success" title="Create Investor">
                <i class="fa fa-plus"></i> Create
            </a>
        </div>
    </div>
@endsection

@section('content')

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Completed</a></li>
            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Activation</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" id="tab_1">
            <table id="datatable" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        {{-- <th></th> --}}
                        {{-- <th>No</th> --}}
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>No Telp</th>
                        <th width="100px">Saldo</th>
                        <th>Investasi</th>
                        <th>Total Asset</th>
                        <th>Register On</th>
                        <th>Date</th>
                        <th width="5%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="tab-pane" id="tab_2">
            <table class="table table-hover table-bordered dua" style="width: 100%;">
                <thead>
                    <tr>
                        {{-- <th></th> --}}
                        {{-- <th>No</th> --}}
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>No Telp</th>
                        {{-- <th>Saldo</th>
                        <th>Investasi</th>
                        <th>Total Asset</th> --}}
                        <th>Register On</th>
                        <th>Date</th>
                        <th width="5%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
    </div>
        
@endsection

@push('scripts')
    <script>
        $('#datatable').DataTable({
            responsive : true,
            processing : true,
            serverSide : true,
            ajax: "{{ route('table.investor') }}",
            columns: [
                {data : 'full_name', name : 'full_name'},
                {data : 'email', name : 'email'},
                {data : 'phone', name : 'phone'},
                {data : 'member_id', name : 'member_id'},
                {data : 'created_at_investor', name : 'created_at_investor'},
                {data : 'updated_at_currency', name : 'updated_at_currency'},
                {data : 'register_on', name : 'register_on'},
                {data : 'created_at_emp', name : 'created_at_emp'},
                {data : 'action', name : 'action'}
            ]
        });

        $('.dua').DataTable({
            responsive : true,
            processing : true,
            serverSide : true,
            ajax: "{{ route('table.investor2') }}",
            columns: [
                {data : 'full_name', name : 'full_name'},
                {data : 'email', name : 'email'},
                {data : 'phone', name : 'phone'},
                // {data : 'member_id', name : 'member_id'},
                // {data : 'created_at_investor', name : 'created_at_investor'},
                // {data : 'updated_at_currency', name : 'updated_at_currency'},
                {data : 'register_on', name : 'register_on'},
                {data : 'created_at_emp', name : 'created_at_emp'},
                {data : 'action', name : 'action'}
            ]
        });
    </script>
@endpush