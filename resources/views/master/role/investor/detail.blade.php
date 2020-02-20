@extends('base.main')
@section('title') Investor @endsection
@section('page_icon') <i class="fa fa-users"></i> @endsection
@section('page_title') Investor {{ $model->title }} @endsection
@section('page_subtitle') detail @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
        	<a href="{{ route('investor.edit', $model->id) }}" class="btn btn-success" title="Edit Employee">
                <i class="fa fa-edit"></i> Update
            </a>
             {{-- onclick="return confirm('Anda yakin?')" --}}
            <a href="{{ route('investor.delete', $model->id) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
        	<a href="{{ route('investor.create') }}" class="btn btn-success" title="Create Employee">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('investor.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection


@section('content')


<div class="box box-solid">
	<div class="box-body">
		<h3>Profile</h3>
		<div class="row">
			<div class="col-md-6">
				<table class="table table-condensed table-striped">
					<tbody>
						
						<tr>
							<th>Full name</th>
							<td>{{ $model->full_name }}</td>
						</tr>
						<tr>
							<th>Username</th>
							<td>{{ $model->username }}</td>
						</tr>
						<tr>
							<th>Gender</th>
							<td>{{ $model->gender == 'M' ? 'Male' : 'Female' }}</td>
						</tr>
						<tr>
							<th>Birth place</th>
							<td>{{ $model->birth_place }}</td>
						</tr>
						<tr>
							<th>Birth date</th>
							<td>{{ date('d-m-Y', strtotime($model->birth_date)) }}</td>
						</tr>
						<tr>
							<th>Email</th>
							<td>{{ $model->email }}</td>
						</tr>
						<tr>
							<th>No Telepon</th>
							<td>{{ $model->phone }}</td>
						</tr>
						<tr>
							<th>Class</th>
							<td>{{ $page->nm_member }}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-3">
				@if($model->photo == null)
					<img src="/images/no-img.png" class="img-responsive img-thumbnail">
				@else
					<img style='width: 220px;height:210px;' src="/images/investor/{{ $model->photo }}" class="img-responsive img-thumbnail">
				@endif
			</div>


		</div>


		<h3>File foto_berkass</h3>
		<div class="row">
			<div class="col-md-6">
				<table class="table table-condensed table-striped">
					<tbody>
						<tr>
							<th>No KTP</th>
							<td>{{ $model->ktp_number }}</td>
						</tr>
						
						<tr>
							<th></th>
							<td></td>
						</tr>
						<tr>
							<th>No NPWP</th>
							<td>{{ $model->npwp_number }}</td>
						</tr>
						
						
					</tbody>
				</table>
			</div>
			<div class="col-md-3">
				@if($model->photo == null)
					<img src="/images/no-img.png" class="img-responsive img-thumbnail">
				@else
					<img style='width: 220px;height:210px;' src="/images/investor/foto_berkas/{{ $model->ktp_photo }}" class="img-responsive img-thumbnail"> 
				@endif
					<tr>
						<th><i>*Images of ktp</i></th>
					</tr>
			</div>
			<div class="col-md-3" style='margin-left: -50px;'>
				@if($model->photo == null)
					<img src="/images/no-img.png" class="img-responsive img-thumbnail">
				@else
					<img style='width: 220px;height:210px;' src="/images/investor/foto_berkas/{{ $model->npwp_photo }}" class="img-responsive img-thumbnail"> 
				@endif
				<tr>
						<th><i>*Images of npwp</i></th>
						
					</tr>
			</div>
			
		</div>

		<h3>Address</h3>
		<div class="row">
			<div class="col-md-12">
				<table class="table table-condensed table-striped">
					<tbody>
						<tr>
							<th>Country</th>
							<td>{{ $address['country'] }}</td>
							<th>Province</th>
							<td>{{ $address['province'] }}</td>
						</tr>
						<tr>
							<th>Regency</th>
							<td>{{ $address['regency'] }}</td>
							<th>District</th>
							<td>{{ $address['district'] }}</td>
						</tr>
						<tr>
							<th>Village</th>
							<td>{{ $address['village'] }}</td>
							<th>Address</th>
							<td>{!! html_entity_decode($model->address) !!}</td>
						</tr>
						<tr>
							<th>Zip Code</th>
							<td>{{ $model->zip_code }}</td>
							<th></th>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<h3>Contact</h3>
		<div class="row">
			<div class="col-md-12">
				<table class="table table-condensed table-striped">
					<tbody>
						<tr>
							<th>Email</th>
							<td>{{ $model->email }}</td>
							
						</tr>
						<tr>
							<th>No Telepon</th>
							<td>{{ $model->phone }}</td>
							
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<h3>Status</h3>
		<div class="row">
			<div class="col-md-12">
				<table class="table table-condensed table-striped">
					<tbody>
						<tr>
							<th>Active</th>
							<td>{{ $model->active == 1 ? 'Ya' : 'Tidak' }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection