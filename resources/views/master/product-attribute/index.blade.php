@extends('base.main')
@section('title') Attribute Product @endsection
@section('page_icon') <i class="fa fa-bookmark-o"></i> @endsection
@section('page_title') Attribute Product @endsection
@section('page_subtitle') list @endsection

@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
           <a href="{{ route('atribute-product.create') }}" class="modal-show btn bg-aqua btn-sm pull-right"><i class="fa fa-plus"></i> Create</a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-info">
        <div class="box-body">
            
            <div class="box-body">
                <table id="datatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Attribute Name </th>
                            <th width="12%">Created By</th>
                            <th width="12%">Created At</th>
                            <th width="10%"><i class="fa fa-cog"> Action</i></th>
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
            ajax: "{{ route('table.product-attribute') }}",
            columns: [
                // {data : 'checkbox', name : 'checkbox'},
                {data : 'DT_Row_Index', name : 'id'},
                {data : 'name', name : 'name'},
                {data : 'created_by', name : 'created_by'},
                {data : 'created_at', name : 'created_at'},
                {data : 'action', name : 'action'}
            ]
        });
    </script>
@endpush
