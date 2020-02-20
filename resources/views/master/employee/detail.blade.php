@extends('base.main')
@section('title') Employee @endsection
@section('page_icon') <i class="fa fa-users"></i> @endsection
@section('page_title') Employee {{ $model->title }} @endsection
@section('page_subtitle') detail @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
        	<a href="{{ route('employee.edit', base64_encode($model->id) ) }}" class="btn btn-success" title="Edit Employee">
                <i class="fa fa-edit"></i> Update
            </a>
             {{-- onclick="return confirm('Anda yakin?')" --}}
            <a href="{{ route('employee.delete', base64_encode($model->id) ) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
        	<a href="{{ route('employee.create') }}" class="btn btn-success" title="Create Employee">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('employee.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
	<div class="box-body">
		<div class="box-body">
			<div class="row">
				<div class="col-md-6">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<td colspan="2">
									<center>
										@if($model->photo == null)
											<img src="/images/no-img.png" class="img-thumbnail" width="300px" />
										@else
											<img src="/images/employee/{{ $model->photo }}" class="img-thumbnail" width="300px" />
										@endif
									</center>
								</td>
							</tr>
							<tr>
								<th width="30%">Username</th>
								<td>{{ $model->username }}</td>
							</tr>
							<tr>
								<th>Full name</th>
								<td>{{ $model->full_name }}</td>
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
								<td>{{ date('d M Y', strtotime($model->birth_date)) }}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-md-6">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<th width="30%">Email</th>
								<td>{{ $model->email }}</td>
							</tr>
							<tr>
								<th>Phone</th>
								<td>{{ $model->phone }}</td>
							</tr>
						</tbody>
					</table>
					<table class="table table-bordered">
						<tbody>
							<tr>
								<th width="30%">Address</th>
								<td>{{ $model->address }}</td>
							</tr>
							<tr>
								<th>Country</th>
								<td>{{ $address['country'] }}</td>
							</tr>
							<tr>
								<th>Province</th>
								<td>{{ $address['province'] }}</td>
							</tr>
							<tr>
								<th>Regency</th>
								<td>{{ $address['regency'] }}</td>
							</tr>
							<tr>
								<th>District</th>
								<td>{{ $address['district'] }}</td>
							</tr>
							<tr>
								<th>Village</th>
								<td>{{ $address['village'] }}</td>
							</tr>
							<tr>
								<th>Zip Code</th>
								<td>{{ $model->zip_code }}</td>
							</tr>
						</tbody>
					</table>
					<table class="table table-bordered">
						<tbody>
							<tr>
								<th width="30%">Role</th>
								<td>{{ $model->role->role }}</td>
							</tr>
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
</div>
@endsection