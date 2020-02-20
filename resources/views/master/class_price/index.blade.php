@extends('base.main')
@section('title') Price Class @endsection
@section('page_icon') <i class="fa fa-money"></i> @endsection
@section('page_title') Price Class @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('class-price.create') }}" class="modal-show btn btn-success" title="Create Price Class">
                <i class="fa fa-plus"></i> Create
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="box box-solid">
        <div class="box-header">
            <button class="btn btn-sm btn-danger btn-mass-delete"><i class="fa fa-trash"></i></button>
        </div>
        <div class="box-body">
            <table id="datatable" class="table table-hover table-condensed">
                <thead>
                    <tr>
                        {{-- <th></th> --}}
                        <th>No</th>
                        <th>Class Name</th>
                        <th>Range Harga</th>
                        <th>Created By</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#datatable').DataTable({
            responsive : true,
            processing : true,
            serverSide : true,
            ajax: "{{ route('table.MstClassPrice') }}",
            columns: [
                // {data : 'checkbox', name : 'checkbox'},
                {data : 'DT_Row_Index', name : 'id'},
                {data : 'class_id', name : 'class_id'},
                {data : 'price_range', name : 'price_range'},
                {data : 'created_by', name : 'created_by'},
                {data : 'created_at', name : 'created_at'},
                {data : 'action', name : 'action'}
            ]
        });
    </script>
@endpush