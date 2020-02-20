@extends('base.main')
@section('title') District @endsection
@section('page_icon') <i class="fa fa-user"></i> @endsection
@section('page_title') District @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
			{{-- <a href="/master/district/import"> <img src="/images/icon-excel.png" height="35"></a>&nbsp;&nbsp; --}}
            <a href="{{ route('district.create') }}" class="btn btn-success modal-show2" title="Create District">
                <i class="fa fa-plus"></i> Create
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="box box-solid">
        <div class="box-body">
            <div class="box-body">
                
    			<table id="datatable" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Regency</th>
                            <th>Name</th>
                            <th width="10%">Action</th>
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
    ajax: "{{ route('table.district') }}",
    columns: [
        {data : 'DT_Row_Index', name : 'id'},
        {data : 'regency', name : 'regency'},
        {data : 'name', name : 'name'},
        {data : 'action', name : 'action'}
    ]
});

$('body').on('change','.dynamic',function(){
    if($(this).val() != ''){
        var id = $(this).attr('id'),
            value = $(this).val(),
            table = $(this).data('table'),
            key = $(this).data('key'),
            token = $('input[name="_token"]').val();

        $.ajax({
            url : "{{ route('employee.fetch') }}",
            method : 'post',
            data : {
                id : id,
                value : value,
                _token : token,
                table : table,
                key : key
            },
            success : function(result){
                $('#'+table).html(result);
            }
        });
    }
});

</script>
@endpush