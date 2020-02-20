@extends('base.main')
@section('title') Data Address @endsection
@section('page_icon') <i class="fa fa-user"></i> @endsection
@section('page_title') Address @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="/master/address/create" class="btn btn-success" title="Create Address">
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
                        <th>Country</th>
                        <th>Province</th>
                        <th>Regency</th>
                        <th>District</th>
                        <th>Village</th>
                        <th>Created Date</th>
                        <!--th>Action</th-->
                        
                    </tr>
                </thead>
				<?php $no = 1;?>
				@foreach($data as $row)
				<tr>
					<td>{{$no}}</td>
					<td>{{$row->country_id}}</td>
					<td>{{$row->province_id}}</td>
					<td>{{$row->regency_id}}</td>
					<td>{{$row->district_id}}</td>
					<td>{{$row->village_id}}</td>
					<td>{{$row->created_at}}</td>
					
					<!--td>
						<a href="/master/address/show/{{$row->id}}" class="btn-xs btn-primary"><i class="fa fa-search"></i></a>
						<a href="/master/address/edit_address/{{$row->id}}" class="btn-xs btn-primary edit" ><i class="fa fa-edit"></i></a>
						<a href="/master/address/delete_address/{{$row->id}}" class="btn-xs btn-danger"><i class="fa fa-trash"></i></a>
					</td-->
					</tr>
				<?php $no++; ?>	
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