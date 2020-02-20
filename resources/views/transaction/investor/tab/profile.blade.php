{!! Form::model($model, [
'route' => ['investor.update', base64_encode($model->id)],
'enctype' => 'multipart/form-data',
'id' => 'profile-form'
]) !!}
{{ Form::hidden('_method', 'PUT', []) }}

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
                <label for="password" class="control-label">Password*</label>
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
                <label for="password_confirmation" class="control-label">Re-Type Password*</label>
                {!! Form::password('password_confirmation', ['class'=>'form-control', 'id'=>'password_confirmation'])
                !!}

                @if($errors->has('password_confirmation'))
                <span class="invalid-feedback" role="alert">
                    {{ $errors->first('password_confirmation') }}
                </span>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="currency_code" class="control-label">Currency</label>
                {!! Form::select('currency_code', ['' => '- Select -'] + $currency , $model->currency_code,
                ['class'=>'form-control', 'id'=>'currency_code']) !!}

                @if($errors->has('currency_code'))
                <span class="invalid-feedback" role="alert">
                    {{ $errors->first('currency_code') }}
                </span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <h3>Profile</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username" class="control-label">Full Name*</label>
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
                        {!! Form::select('gender', [''=>'- Select -']+['M'=>'Male','F'=>'Female'], null,
                        ['class'=>'form-control', 'id'=>'gender']) !!}

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
                        <label for="birth_place" class="control-label">Birth Place</label>
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
                        <label for="birth_date" class="control-label">Birth Date</label>
                        {{-- {!! Form::text('birth_date', null, ['class'=>'form-control', 'id'=>'datepicker']) !!} --}}

                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="birth_date" type="text" class="form-control pull-right" id="datepicker"
                                value="{{ date('d-m-Y', strtotime($model->birth_date)) }}">
                        </div>

                        @if($errors->has('birth_date'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('birth_date') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <h3>Contact</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="zip_code" class="control-label">Email*</label>
                        {!! Form::text('email', null, ['class'=>'form-control', 'id'=>'email']) !!}

                        @if($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('email') }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="zip_code" class="control-label">Phone</label>
                        {!! Form::text('phone', null, ['class'=>'form-control', 'id'=>'phone']) !!}

                        @if($errors->has('phone'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('phone') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <h3>Address</h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="desc" class="control-label">Address</label>
                        {!! Form::textarea('address', null, ['class'=>'form-control', 'id'=>'address', 'rows'=>3]) !!}

                        @if($errors->has('address'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('address') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="countries_id" class="control-label">Country</label>
                        {!! Form::select('countries_id', ['' => '- Select -']+$countries , null,
                        ['class'=>'form-control', 'id'=>'countries_id']) !!}

                        @if($errors->has('countries_id'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('countries_id') }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="zip_code" class="control-label">Zip Code</label>
                        {!! Form::text('zip_code', null, ['class'=>'form-control', 'id'=>'zip_code']) !!}

                        @if($errors->has('zip_code'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('zip_code') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <h3>Attachment</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ktp_photo" class="control-label">Identity Card Photo</label>
                        @if($model->ktp_photo != '')
                        <br>
                        <img src="/images/investor/foto_berkas/{{ $model->ktp_photo }}" class="img-responsive" />
                        @endif
                    </div>
                    <div class="form-group">
                        {!! Form::file('ktp_photo', ['accept'=>'image/x-png, image/jpeg']) !!}
                    </div>
                    <div class="form-group">
                        <label for="zip_code" class="control-label">Identity Card Number*</label>
                        {!! Form::text('ktp_number', null, ['class'=>'form-control', 'id'=>'ktp_number']) !!}

                        @if($errors->has('ktp_photo'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('ktp_photo') }}
                        </span>
                        @endif
                        @if($errors->has('ktp_number'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('ktp_number') }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="npwp_photo" class="control-label">Taxpayer Registration Photo</label>
                        @if($model->ktp_photo != '')
                        <br>
                        <img src="/images/investor/foto_berkas/{{ $model->npwp_photo }}" class="img-responsive" />
                        @endif
                    </div>
                    <div class="form-group">
                        {!! Form::file('npwp_photo', ['accept'=>'image/x-png, image/jpeg']) !!}
                    </div>
                    <div class="form-group">
                        <label for="zip_code" class="control-label">Taxpayer Registration Number</label>
                        {!! Form::text('npwp_number', null, ['class'=>'form-control', 'id'=>'npwp_number']) !!}

                        @if($errors->has('npwp_photo'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('npwp_photo') }}
                        </span>
                        @endif
                        @if($errors->has('npwp_number'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('npwp_number') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="active" class="control-label">Active</label>
                        {!! Form::select('active', [1=>'Active',0=>'Inactive'], null,
                        ['class'=>'form-control','id'=>'active']); !!}
                        @if($errors->has('active'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('active') }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="is_dummy" class="control-label">Is Dummy?</label>
                        {!! Form::select('is_dummy', [''=>'Choose',1=>'Yes',0=>'No'], null,
                        ['class'=>'form-control','id'=>'active']); !!}
                        @if($errors->has('is_dummy'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('is_dummy') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" style="margin-top: 55px;">
                        <label for="image" class="control-label">Image</label>
                        @if($model->photo != '')
                        <br>
                        <img src="/images/investor/{{ $model->photo }}" class="img-thumbnail" width="180px">
                        @endif
                    </div>
                    <div class="form-group">
                        {!! Form::file('photo', ['accept'=>'image/x-png, image/jpeg']) !!}

                        @if($errors->has('photo'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('photo') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="box-footer">
    {!! Form::submit('Save', ['class'=>'btn btn-primary pull-right profile-submit-btn']) !!}
</div>
{!! Form::close() !!}