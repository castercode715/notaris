@extends('base.main')
@section('title') Province @endsection
@section('page_icon') <i class="fa fa-user"></i> @endsection
@section('page_title') Province @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
			<a href="/master/province/import"> <img src="/images/icon-excel.png" height="35"></a>&nbsp;&nbsp; 
            <a href="/master/province/create" class="btn btn-success" title="Create Country">
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
                        <th>#</th>
                        <th>No</th>
                       
                        <th>Country</th>
                        <th>Province</th>
                        <th>Action</th>
                        
                    </tr>
                </thead>
				@php $no = 1; @endphp
				@foreach($model as $data)
				<tr>
					<td><input type="checkbox"></td>
					<td>{{$no++}}</td>
					
					<td>{{$data->country}}</td>
					<td>{{$data->name}}</td>
					<td>
						<a href="/master/province/edit/{{$data->id}}" class="btn-xs btn-primary edit" ><i class="fa fa-edit"></i></a>
						<a href="/master/province/delete/{{$data->id}}" class="btn-xs btn-danger"><i class="fa fa-trash"></i></a>
					</td>
					</tr>
				@endforeach
				
            </table>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $('#datatable').DataTable({
            responsive : true,
            processing : true,
           
        });
    </script>
@endpush