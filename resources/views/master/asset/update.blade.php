@extends('base.main')
@section('title') Asset @endsection
@section('page_icon') <i class="fa fa-cube"></i> @endsection
@section('page_title') Edit Asset @endsection
@section('page_subtitle') edit @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('asset.show', base64_encode($model->id)) }}" class="btn btn-success" title="Detail Asset">
                <i class="fa fa-search"></i> Detail
            </a>
             {{-- onclick="return confirm('Anda yakin?')" --}}
            <a href="{{ route('asset.delete', base64_encode($model->id)) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
        	<a href="{{ route('asset.create') }}" class="btn btn-success" title="Create Asset">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('asset.index') }}" class="btn btn-success" title="Manage Asset">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => ['asset.update', $model->id],
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

            <h3>Detail</h3>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="category_asset_id" class="control-label">Category*</label>
                        {!! Form::select('category_asset_id', [''=>'- Select -'] + $category, null, ['class'=>'form-control dynamic2', 'id'=>'category_asset_id']) !!}

                        @if($errors->has('category_asset_id'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('category_asset_id') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="class_id" class="control-label">Class*</label>
                        {!! Form::select('class_id', [''=>'- Select -'] + $class, null, ['class'=>'form-control', 'id'=>'class_id']) !!}

                        @if($errors->has('class_id'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('class_id') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="terms_conds_id" class="control-label">Term & Conditions*</label>
                        {!! Form::select('terms_conds_id', [''=>'- Select -'] + $termsconds, null, ['class'=>'form-control', 'id'=>'terms_conds_id']) !!}

                        @if($errors->has('terms_conds_id'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('terms_conds_id') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="interest" class="control-label">Interest*</label>
                        {{-- {!! Form::select('interest', [''=>'- Select -']+['18'=>'18 %','19'=>'19 %','22'=>'22 %'], null, ['class'=>'form-control', 'id'=>'interest', 'required'=>'required']) !!} --}}
                        <div class="input-group">
                            {!! Form::number('interest', null, ['class'=>'form-control', 'id'=>'interest', 'required'=>'required']) !!}
                            <span class="input-group-addon">
                                %
                            </span>
                        </div>

                        @if($errors->has('interest'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('interest') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="price_market" class="control-label">Market Price*</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                Rp
                            </span>
                            {!! Form::text('price_market', null, ['class'=>'form-control', 'id'=>'price_market']) !!}
                        </div>

                        @if($errors->has('price_market'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('price_market') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="price_liquidation" class="control-label">Liquidation Price*</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                Rp
                            </span>
                            {!! Form::text('price_liquidation', null, ['class'=>'form-control', 'id'=>'price_liquidation']) !!}
                        </div>

                        @if($errors->has('price_liquidation'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('price_liquidation') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="price_selling" class="control-label">Selling Price*</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                Rp
                            </span>
                            {!! Form::text('price_selling', null, ['class'=>'form-control', 'id'=>'price_selling']) !!}
                        </div>

                        @if($errors->has('price_selling'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('price_selling') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="price_loan" class="control-label">Loan Price*</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                Rp
                            </span>
                            {!! Form::text('price_loan', null, ['class'=>'form-control', 'id'=>'price_loan', 'required'=>'required']) !!}
                        </div>

                        @if($errors->has('price_loan'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('price_loan') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date_available" class="control-label">Start Date*</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="date_available" type="text" class="form-control pull-right date_start" id="datepicker" value="{{ date('d-m-Y', strtotime($model->date_available)) }}">
                        </div>

                        @if($errors->has('date_available'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('date_available') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="credit_tenor" class="control-label">Credit Tenor*</label>
                        {{-- {!! Form::select('credit_tenor', [''=>'- Select -'] + $tenorCredit, null, ['class'=>'form-control', 'id'=>'credit_tenor']) !!} --}}
                        <div class="input-group">
                            {!! Form::number('credit_tenor', null, ['class'=>'form-control', 'id'=>'credit_tenor', 'required'=>'required']) !!}
                            <span class="input-group-addon">
                                Days
                            </span>
                        </div>

                        @if($errors->has('credit_tenor'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('credit_tenor') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date_expired" class="control-label">Expired Date*</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="date_expired" type="text" class="form-control pull-right" id="date_end" value="{{ date('d-m-Y', strtotime($model->date_expired)) }}" readonly>
                        </div>

                        @if($errors->has('date_expired'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('date_expired') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <hr>

            <h3>Attribute</h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="attributes_wrapper">
                        {{-- @if(!empty($attributes)) --}}
                        @php $count = 0; @endphp
                        @foreach($attributes as $attribute)

                            <div class="form-group attr-form-group row">
                                <div class="col-md-5">
                                    @if($count < 1) <label for="attr_name" class="control-label">Attribute Name</label> @endif
                                    <select name="attr_name[]" id="attr_name" class="form-control attr_name" required="required">
                                        <option value="">- Select -</option>
                                        @foreach($attributeList as $value)
                                            @if($value->id == $attribute->attr_asset_id)
                                                <option value="{!! $value->id !!}" selected>{!! $value->description !!}</option>
                                            @else
                                                <option value="{!! $value->id !!}">{!! $value->description !!}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    @if($count < 1) <label for="attr_value" class="control-label">Attribute Value</label> @endif
                                    <input type="text" name="attr_value[]" class="form-control"  value="{!! $attribute->value !!}" required="required">
                                </div>
                                <div class="col-md-2">
                                @if($count < 1)
                                    <a href="javascript:void(0);" class="add_attr_button btn btn-sm btn-success" title="Add Field"  style="margin-top: 25px;"><i class="fa fa-plus"></i></a>
                                @else
                                    <a href="javascript:void(0);" class="remove_attr_button btn btn-sm btn-danger" title="Remove"><i class="fa fa-close"></i></a>
                                @endif
                                </div>
                            </div>

                            @php $count++; @endphp
                        @endforeach
                        {{-- @endif --}}
                    </div>
                    @if($errors->has('attr_name.*'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('attr_name.*') }}
                        </span>
                    @endif
                    @if($errors->has('attr_value.*'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('attr_value.*') }}
                        </span>
                    @endif
                    <p>
                        <a href="javascript:void(0);" class="add_attr_button btn btn-sm btn-success" title="Add Field" style="margin-top: 25px;"><i class="fa fa-plus"></i> Add Attribute Field</a>
                    </p>
                </div>
            </div>
            
            <hr>

            <h3>Images</h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        @if(!empty($images))
                        @php $img = 0; @endphp
                        @foreach($images as $image)
                            <div class="col-sm-3">
                                <div class="img-wrap">
                                    <input type="hidden" name="image_other[]" value="{{ $image->id }}" />
                                    <img src="/images/asset/{{ $image->photo }}" class="">
                                    <button class="btn btn-xs btn-danger img-remove"><i class="fa fa-close"></i></button>
                                </div>
                            </div>
                            @php $img++; @endphp
                        @endforeach
                        @endif
                    </div>
                    @if($errors->has('image_other.*'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('image_other.*') }}
                        </span>
                    @endif
                    <p>
                        <a href="javascript:void(0);" class="add_button btn btn-sm btn-success" title="Add Field" style="margin-top: 25px;"><i class="fa fa-plus"></i> Add Upload Image Field</a>
                    </p>
                    <div class="field_wrapper"></div>
                    <p style="font-style: italic;">Image size : <b>770 x 514 px</b></p>
                    @if($errors->has('images'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('images') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label for="featured_img" class="control-label">Featured Image</label>
                    @if(!empty($featured))
                    <img src="/images/asset/{{ $featured->photo }}" class="img-responsive">
                    @endif
                    {!! Form::file('featured_img', ['accept'=>'image/x-png, image/jpeg, image/jpg', 'class'=>'form-control']) !!}
                    <p style="font-style: italic;"><i>File size: <b>256 x 291 px</b></i></p>
                    @if($errors->has('featured_img'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('featured_img') }}
                        </span>
                    @endif                    
                </div>
                <div class="col-md-4">
                    <label for="banner_new" class="control-label">New Banner</label>
                    @if(!empty($newbanner))
                    <img src="/images/asset/{{ $newbanner->photo }}" class="img-responsive">
                    @endif
                    {!! Form::file('banner_new', ['accept'=>'image/x-png, image/jpeg, image/jpg', 'class'=>'form-control']) !!}
                    <p style="font-style: italic;"><i>File size: <b>1626 x 913 px</b></i></p>
                    @if($errors->has('banner_new'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('banner_new') }}
                        </span>
                    @endif
                </div>
                <div class="col-md-4">
                    <label for="banner_hot" class="control-label">Hot Banner</label>
                    @if(!empty($hotbanner))
                    <img src="/images/asset/{{ $hotbanner->photo }}" class="img-responsive">
                    @endif
                    {!! Form::file('banner_hot', ['accept'=>'image/x-png, image/jpeg, image/jpg', 'class'=>'form-control']) !!}
                    <p style="font-style: italic;"><i>File size: <b>3793 x 1042 px</b></i></p>
                    @if($errors->has('banner_hot'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('banner_hot') }}
                        </span>
                    @endif
                </div>
            </div>

            <hr>

            <h3>Address</h3>
            <div class="row">
                <div class="col-md-4"> 
                    <div class="form-group">
                        <label for="country" class="control-label">Country</label>
                        <select name="country" id="country" class="form-control select2 dynamic" data-table="mst_provinces" data-key="countries_id">
                            <option value="">- Select -</option>
                            @foreach($countriesList as $country)
                                @if(!empty($address))
                                    <option value="{{ $country->id }}" {{ $address->country == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                @else
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endif
                            @endforeach
                        </select>

                        @if($errors->has('country'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('country') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="province" class="control-label">Province</label>
                        <select name="province" id="mst_provinces" class="form-control dynamic" data-table="mst_regencies" data-key="provinces_id">
                            <option value="">- Select -</option>
                            @if(!empty($provincesList))
                            @foreach($provincesList as $province)
                                <option value="{{ $province->id }}" {{ $address->province == $province->id ? 'selected':'' }}>{{ $province->name }}</option>
                            @endforeach
                            @endif
                        </select>

                        @if($errors->has('province'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('province') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="regencies_id" class="control-label">Regency</label>
                        <select name="regencies_id" id="mst_regencies" class="form-control select2">
                            <option value="">- Select -</option>
                            @if(!empty($regenciesList))
                            @foreach($regenciesList as $regency)
                                <option value="{{ $regency->id }}" {{ $address->regency == $regency->id ? 'selected':'' }}>{{ $regency->name }}</option>
                            @endforeach
                            @endif
                        </select>

                        @if($errors->has('regencies_id'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('regencies_id') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <hr>

            <h4>Owner</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="owner_name" class="control-label">Full Name</label>
                        {!! Form::text('owner_name', null, ['class'=>'form-control', 'id'=>'owner_name']) !!}

                        @if($errors->has('owner_name'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('owner_name') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="owner_ktp_number" class="control-label">Identity Card Number</label>
                        {!! Form::text('owner_ktp_number', null, ['class'=>'form-control', 'id'=>'owner_ktp_number']) !!}

                        @if($errors->has('owner_ktp_number'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('owner_ktp_number') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="owner_kk_number" class="control-label">Family Card Number</label>
                        {!! Form::text('owner_kk_number', null, ['class'=>'form-control', 'id'=>'owner_kk_number']) !!}

                        @if($errors->has('owner_kk_number'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('owner_kk_number') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="owner_npwp_number" class="control-label">NPWP Number</label>
                        {!! Form::text('owner_npwp_number', null, ['class'=>'form-control', 'id'=>'owner_npwp_number']) !!}

                        @if($errors->has('owner_npwp_number'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('owner_npwp_number') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- <hr>

            <h3>Status</h3>
            <div class="row">
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="active" class="control-label">Active</label>
                        {!! Form::select('active', [0=>'Inactive', 1=>'Active'], null, ['class'=>'form-control', 'id'=>'active']) !!}

                        @if($errors->has('active'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('active') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div> -->
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

    //Format Number
    var input = document.getElementById('price_market');
    input.addEventListener('keyup', function(e)
    {
        input.value = format_number(this.value);
    });

    var input2 = document.getElementById('price_liquidation');
    input2.addEventListener('keyup', function(e)
    {
        input2.value = format_number(this.value);
    });

    var input3 = document.getElementById('price_selling');
    input3.addEventListener('keyup', function(e)
    {
        input3.value = format_number(this.value);
    });

    var input4 = document.getElementById('price_loan');
    input4.addEventListener('keyup', function(e)
    {
        input4.value = format_number(this.value);
    });

    function format_number(number, prefix, thousand_separator, decimal_separator)
    {
        var thousand_separator = thousand_separator || ',',
            decimal_separator = decimal_separator || '.',
            regex   = new RegExp('[^' + decimal_separator + '\\d]', 'g'),
            number_string = number.replace(regex, '').toString(),
            split   = number_string.split(decimal_separator),
            rest    = split[0].length % 3,
            result    = split[0].substr(0, rest),
            thousands = split[0].substr(rest).match(/\d{3}/g);
        
        if (thousands) {
            separator = rest ? thousand_separator : '';
            result += separator + thousands.join(thousand_separator);
        }

        result = split[1] != undefined ? result + decimal_separator + split[1] : result;
        return prefix == undefined ? result : (result ? prefix + result : '');
    };



    $('.img-remove').click(function(e){
		e.preventDefault();
	    var ithis = $(this);
		ithis.closest('.col-sm-3').remove();
    });
    
    $('.img-close').click(function(e){
        e.preventDefault();
        var key = $(this).data('key'),
            token = $('input[name="_token"]').val();
        var ithis = $(this);
        
        swal({
            title : 'Are you sure ?',
            type : 'warning',
            showCancelButton : true,
            confirmButtonColor : '#3085d6',
            cancelButtonColor : '#d33',
            confirmButtonText : 'Yes, delete!'
        }).then((result)=>{
            if(result.value){

                $.ajax({
                    url : "{{ route('asset.remove-image') }}",
                    method : 'post',
                    data : {
                        value : key,
                        _token : token
                    },
                    success : function(r){
                        status = true;

                        ithis.closest('.img-wrap').remove();

						swal({
	                        type : 'success',
	                        title : 'Success',
	                        text : 'Deleted'
	                    });
					},
					error : function(er){
	                    swal({
	                        type : 'error',
	                        title : 'Failed',
	                        text : 'Failed'
	                    });
	                }
				});
	        }
	    });

	});

	$('.existed_attr_remove_button').click(function(e){
		e.preventDefault();

		var asset = $(this).data('asset'),
			attr = $(this).data('attr'),
			token = $('input[name="_token"]').val();
		var ithis = $(this);

		swal({
	        title : 'Are you sure ?',
	        type : 'warning',
	        showCancelButton : true,
	        confirmButtonColor : '#3085d6',
	        cancelButtonColor : '#d33',
	        confirmButtonText : 'Yes, delete!'
	    }).then((result)=>{
	        if(result.value){

				$.ajax({
					url : "{{ route('asset.remove-attr') }}",
					method : 'post',
					data : {
						asset : asset,
						attr : attr,
						_token : token
					},
					success : function(r){
						status = true;

						ithis.closest('.attr-form-group').remove();

						swal({
	                        type : 'success',
	                        title : 'Success',
	                        text : 'Deleted'
	                    });
					},
					error : function(er){
	                    swal({
	                        type : 'error',
	                        title : 'Failed',
	                        text : 'Failed'
	                    });
	                }
				});
	        }
	    });
	});

    $('.dynamic2').change(function(){
        if($(this).val() != ''){
            var value = $(this).val(),
                token = $('input[name="_token"]').val();

            $.ajax({
                url : "{{ route('asset.fetch-attributes') }}",
                method : 'post',
                data : {
                    value : value,
                    _token : token
                },
                success : function(result){
                    $('.attr_name').html(result);
                }
            });
        }
    });

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

    $('.set-featured').click(function(e){
    	e.preventDefault();
    	// mencari bg-aqua
    	// var f = document.getElementsByClassName("bg-aqua");
    	var f = document.querySelectorAll(".img-wrap.bg-aqua");
        var fbtn = document.querySelectorAll(".set-featured");
    	var closebtn = document.querySelectorAll(".img-close");

    	var id = $(this).data('key'),
    		asset = $(this).data('asset'),
			token = $('input[name="_token"]').val();
	    var ithis = $(this);

	    // console.log(f);
	    swal({
	        title : 'Set this image as featured?',
	        type : 'warning',
	        showCancelButton : true,
	        confirmButtonColor : '#3085d6',
	        cancelButtonColor : '#d33',
	        confirmButtonText : 'Yes!'
	    }).then((result)=>{
	        if(result.value){

				$.ajax({
					url : "{{ route('asset.set-featured') }}",
					method : 'post',
					data : {
						id : id,
						asset : asset,
						_token : token
					},
					success : function(r){
						// change bg old featured
						[].forEach.call(f, function(el) {
						    el.classList.remove("bg-aqua");
						});
                        // show featured btn
                        [].forEach.call(fbtn, function(el) {
                            el.classList.remove("hide");
                        });
						// show remove btn
                        [].forEach.call(closebtn, function(el) {
                            el.classList.remove("hide");
                        });
						// hide featured button
						ithis.addClass("hide");
						ithis.closest('.img-wrap').addClass('bg-aqua');
                        // hide remove btn
                        ithis.closest('.img-wrap').find(".img-close").addClass('hide');

						swal({
	                        type : 'success',
	                        title : 'Success',
	                        text : 'Deleted'
	                    });
					},
					error : function(er){
	                    swal({
	                        type : 'error',
	                        title : 'Failed',
	                        text : 'Failed'
	                    });
	                }
				});
	        }
	    });
    });


	var maxField = 10,
        addButton = $('.add_button'),
		addAttrButton = $('.add_attr_button'),
        wrapper = $('.field_wrapper'),
		attr_wrapper = $('.attributes_wrapper'),
        fieldImgHtml = '<div class="form-group img-form-group row"><div class="col-md-10"><input type="file" name="images[]" class="form-control" accept="image/x-png, image/jpeg"></div><div class="col-md-2"><a href="javascript:void(0);" class="remove_button btn btn-sm btn-danger" title="Remove"><i class="fa fa-close"></i></a></div></div>',
        x = 0,
        y = 1;

	$(addButton).click(function(){
		if(x < maxField){
			x++;
			$(wrapper).append('<div class="form-group img-form-group row"><div class="col-md-6"><input type="file" name="images[]" accept="image/x-png, image/jpeg, image/jpg" required="required"></div><div class="col-md-2"><a href="javascript:void(0);" class="remove_button btn btn-sm btn-danger" title="Remove"><i class="fa fa-close"></i></a></div></div>');
		}
	});

	$(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).closest('.img-form-group').remove();
        // $(this).parent('div').remove(); //Remove field html
        // document.getElementsByClassName("form-group").remove();
        x--; //Decrement field counter
    });

    $(addAttrButton).click(function(){
        if(y < maxField){
            y++;
            $(attr_wrapper).append('<div class="form-group attr-form-group row"><div class="col-md-5"><select name="attr_name[]" class="form-control attr_name attr_'+y+'" id="attr_name"></select></div><div class="col-md-5"><input type="text" name="attr_value[]" class="form-control" required="required"></div><div class="col-md-2"><a href="javascript:void(0);" class="remove_attr_button btn btn-sm btn-danger" title="Remove"><i class="fa fa-close"></i></a></div></div>');

            var value = $('#category_asset_id').val();
            var token = $('input[name="_token"]').val();

            $.ajax({
                url : "{{ route('asset.fetch-attributes') }}",
                method : 'post',
                data : {
                    value : value,
                    _token : token
                },
                success : function(result){
                    $('.attr_'+y).html(result);
                }
            });
        }
    });

    $(attr_wrapper).on('click', '.remove_attr_button', function(e){
        e.preventDefault();
        $(this).closest('.attr-form-group').remove();
        // $(this).parent('div').remove(); //Remove field html
        // document.getElementsByClassName("form-group").remove();
        x--; //Decrement field counter
    });

    $('#credit_tenor').keyup(function(){
        var start_date = $('.date_start').val(),
            tenor = $(this).val();


        if(start_date !== '' && tenor !== '')
        {
            var date = start_date.split('-');
            let xdd = parseInt(date[0]);
            let xmm = parseInt(date[1]);
            let xyy = parseInt(date[2]);

            var s_date = new Date(xmm+'-'+xdd+'-'+xyy);
            s_date.setDate(parseInt(s_date.getDate()) + parseInt(tenor));

            var dd = s_date.getDate();
            var mm = s_date.getMonth() + 1;
            var yy = s_date.getFullYear();
            dd = ("0" + dd).slice(-2);
            mm = ("0" + mm).slice(-2);
            var formattedDate = dd+'-'+mm+'-'+yy;
            // console.log(formattedDate);
            document.getElementById('date_end').value = formattedDate;
        }
    });

</script>
@endpush

@push('css')
<style>
.img-gallery-wrapper {
	/*border: 1px solid #d2d6de;*/
	/*background: #f1f1f1;*/
	margin-bottom: 10px;
}	
.img-wrap {
	width: 100%;
    position: relative;
    margin: 0px 5px 5px 0px;
    float: left;
    background-color: #fafafa;
    border-radius: 4px;
    border: 1px solid #e6e4e4;
    padding: 3px;
}
.featured {
    background-color: #f39c12;
}
.img-wrap img {
    width: 80%;
    height: 130px;
    margin-right: 10px;
}
.hide {
	display: none;
}
/*.img-wrap .img-close {
    position: absolute;
    top: 46%;
    left: 92%;
    transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    background-color: #dd4b39;
    color: white;
    font-size: 12px;
    padding: 4px 8px;
    border: none;
    cursor: pointer;
    border-radius: 50%;
    text-align: center;
    opacity: 0.8;
}
.img-wrap:hover .img-close {
    opacity: 1;
    background-color: #dd4b39;
}*/
</style>
@endpush