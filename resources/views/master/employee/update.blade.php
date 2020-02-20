@extends('base.main')
@section('title') Employee @endsection
@section('page_icon') <i class="fa fa-users"></i> @endsection
@section('page_title') Edit Employee @endsection
@section('page_subtitle') Edit @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('employee.show', base64_encode($model->id) ) }}" class="btn btn-success" title="Show Employee">
                <i class="fa fa-search"></i> Detail
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
    {!! Form::model($model, [
        'route' => ['employee.update', base64_encode($model->id) ],
        'method'=> 'put',
        'enctype'   => 'multipart/form-data'
    ]) !!}

    <div class="box-body">
        <div class="box-body">
            @if(count($errors) > 0)
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{  $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <h3>Account</h3>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="username" class="control-label">Username*</label>
                        {!! Form::text('username', null, ['class'=>'form-control', 'id'=>'username']) !!}

                        @if($errors->has('username'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('username') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="password" class="control-label">Password</label>
                        {!! Form::password('password', ['class'=>'form-control', 'id'=>'password']) !!}

                        @if($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('password') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="password_confirmation" class="control-label">Re-Type Password</label>
                        {!! Form::password('password_confirmation', ['class'=>'form-control', 'id'=>'password_confirmation']) !!}

                        @if($errors->has('password_confirmation'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('password_confirmation') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <h3>Profile</h3>
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="full_name" class="control-label">Full name*</label>
                                {!! Form::text('full_name', null, ['class'=>'form-control', 'id'=>'full_name']) !!}

                                @if($errors->has('full_name'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('full_name') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender" class="control-label">Gender*</label>
                                {!! Form::select('gender', [''=>'- Select -']+['M'=>'Male','F'=>'Female'], null, ['class'=>'form-control', 'id'=>'gender']) !!}

                                @if($errors->has('gender'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('gender') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="birth_place" class="control-label">Birth Place*</label>
                                {!! Form::text('birth_place', null, ['class'=>'form-control', 'id'=>'birth_place']) !!}

                                @if($errors->has('birth_place'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('birth_place') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="birth_date" class="control-label">Birth Date*</label>

                                <input type="hidden" name="hidden_birth_date">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input name="birth_date" type="text" class="form-control pull-right" id="datepicker" value="{{ date('d-m-Y', strtotime($model->birth_date)) }}">
                                </div>

                                @if($errors->has('birth_date'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('birth_date') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <h3>Address</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address" class="control-label">Address*</label>
                                {!! Form::textarea('address', null, ['class'=>'form-control', 'id'=>'address', 'rows'=>3]) !!}

                                @if($errors->has('address'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('address') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="photo" class="control-label">Photo*</label>
                        @if($model->photo)
                        <p>
                            <img src="/images/employee/{{ $model->photo }}" class="img-thumbnail" width="200px" />
                        </p>
                        @endif
                        {!! Form::file('photo', ['accept'=>'image/x-png, image/jpeg']) !!}

                        @if($errors->has('photo'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('photo') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="row">
                {{-- country --}}
                <div class="col-md-4"> 
                    <div class="form-group">
                        <label for="country" class="control-label">Country*</label>
                        <select name="country" id="country" class="form-control dynamic" data-table="mst_provinces" data-key="countries_id">
                            <option value="">- Select -</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ $address['country_id']==$country->id?'selected':'' }}>{{ $country->name }}</option>
                            @endforeach
                        </select>

                        @if($errors->has('country'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('country') }}
                            </span>
                        @endif
                	</div>
                </div>
                {{-- province --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="province" class="control-label">Province*</label>
                        <select name="province" id="mst_provinces" class="form-control dynamic" data-table="mst_regencies" data-key="provinces_id">
                            <option value="">- Select -</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}" {{ $address['province_id']==$province->id?'selected':'' }}>{{ $province->name }}</option>
                            @endforeach
                        </select>

                        @if($errors->has('province'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('province') }}
                            </span>
                        @endif
                    </div>
                </div>
                {{-- regency --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="regency" class="control-label">Regency*</label>
                        <select name="regency" id="mst_regencies" class="form-control dynamic" data-table="mst_districts" data-key="regencies_id">
                            <option value="">- Select -</option>
                            @foreach($regencies as $regency)
                                <option value="{{ $regency->id }}" {{ $address['regency_id']==$regency->id?'selected':'' }}>{{ $regency->name }}</option>
                            @endforeach
                        </select>

                        @if($errors->has('regency'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('regency') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- district --}}
                <div class="col-md-4"> 
                    <div class="form-group">
                        <label for="district" class="control-label">District*</label>
                        <select name="district" id="mst_districts" class="form-control dynamic" data-table="mst_villages" data-key="districts_id">
                            <option value="">- Select -</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{ $address['district_id']==$district->id?'selected':'' }}>{{ $district->name }}</option>
                            @endforeach
                        </select>

                        @if($errors->has('district'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('district') }}
                            </span>
                        @endif
                    </div>
                </div>
                {{-- village --}}
                <div class="col-md-4"> 
                    <div class="form-group">
                        <label for="villages_id" class="control-label">Village*</label>
                        <select name="villages_id" id="mst_villages" class="form-control">
                            <option value="">- Select -</option>
                            @foreach($villages as $village)
                                <option value="{{ $village->id }}" {{ $address['village_id']==$village->id?'selected':'' }}>{{ $village->name }}</option>
                            @endforeach
                        </select>

                        @if($errors->has('villages_id'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('villages_id') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="zip_code" class="control-label">Zip Code*</label>
                        {!! Form::text('zip_code', null, ['class'=>'form-control', 'id'=>'zip_code']) !!}

                        @if($errors->has('zip_code'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('zip_code') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <h3>Contact</h3>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="email" class="control-label">Email*</label>
                        {!! Form::text('email', null, ['class'=>'form-control', 'id'=>'email']) !!}

                        @if($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="phone" class="control-label">Phone*</label>
                        {!! Form::text('phone', null, ['class'=>'form-control', 'id'=>'phone']) !!}

                        @if($errors->has('phone'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('phone') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4"> 
                    <div class="form-group">
                        <label for="role_id" class="control-label">Role</label>
                        {!! Form::select('role_id', [''=>'- Select -'] + $roles, null, ['class'=>'form-control', 'id'=>'role']) !!}

                        @if($errors->has('role'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('role') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="active" class="control-label">Active</label>
                        <div>
                            {!! Form::checkbox('active', null, null, ['id'=>'active']) !!} 
                        </div>

                        @if($errors->has('active'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('active') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="box-footer">
        {!! Form::submit('Save', ['class'=>'btn btn-primary pull-right']) !!}
    </div>

    {!! Form::close() !!}
</div>
@endsection

@push('scripts')
<script>
	$('.dynamic').change(function(){
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