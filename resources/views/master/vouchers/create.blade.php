@extends('base.main')
@section('title') Voucher @endsection
@section('page_icon') <i class="fa fa-id-card"></i> @endsection
@section('page_title') Create Voucher @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('vouchers.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => 'vouchers.store',
        'method'=> 'post',
        'enctype'   => 'multipart/form-data'
    ]) !!}

    <div class="box-body">
	    <div class="box-body">
	    	<p class="note"><i>Fields with <span class="required">*</span> are required.</i></p>
	    	
	    	@if(count($errors) > 0)
	    	<div class="alert alert-danger alert-dismissible" role="alert">
  				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
  					<span aria-hidden="true">&times;</span>
  				</button>
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{  $error }}</li>
                    @endforeach
                </ul>
	        </div>
	        @endif

	        <div class="row">
	        	<div class="col-md-2">
	        		<div class="form-group">
                    	<label for="language" class="control-label">Language*</label>
                    	{!! Form::text('language', $language->language, ['class'=>'form-control', 'id'=>'language', 'readonly'=>'readonly']) !!}
                        {!! Form::hidden('code', $language->code, []) !!}

                    	@if($errors->has('language'))
	                        <span class="invalid-feedback" role="alert">
	                            {{ $errors->first('language') }}
	                        </span>
	                    @endif
                    </div>
	        	</div>	
	        	<div class="col-md-2">
	        		<div class="form-group">
                    	<label for="code" class="control-label">Code*</label>
                    	{!! Form::text('code', null, ['class'=>'form-control', 'id'=>'code']) !!}

                    	@if($errors->has('code'))
	                        <span class="invalid-feedback" role="alert">
	                            {{ $errors->first('code') }}
	                        </span>
	                    @endif
                    </div>
	        	</div>	
	        	<div class="col-md-8">
	        		<div class="form-group">
                    	<label for="name" class="control-label">Name*</label>
                    	{!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}

                    	@if($errors->has('name'))
	                        <span class="invalid-feedback" role="alert">
	                            {{ $errors->first('name') }}
	                        </span>
	                    @endif
	        		</div>
	        	</div>
	        </div>
	        <div class="row">
	        	<div class="col-md-4">
	        		<div class="form-group">
	        			<label for="type" class="control-label">Type*</label>
                    	{!! Form::select('type', ['' => '- Choose -', 'PUBLIC'=>'Public', 'PRIVATE'=>'Private'], null, ['class'=>'form-control', 'id'=>'type']) !!}

                    	@if($errors->has('type'))
	                        <span class="invalid-feedback" role="alert">
	                            {{ $errors->first('type') }}
	                        </span>
	                    @endif	
	        		</div>
	        	</div>
	        	<div class="col-md-4">
	        		<div class="form-group">
	        			<label for="value_type" class="control-label">Value Type*</label>
                    	{!! Form::select('value_type', [''=>'- Choose -', 'PRECENTAGE'=>'Precentage', 'NOMINAL'=>'Nominal'], null, ['class'=>'form-control', 'id'=>'value_type']) !!}

                    	@if($errors->has('value_type'))
	                        <span class="invalid-feedback" role="alert">
	                            {{ $errors->first('value_type') }}
	                        </span>
	                    @endif
	        		</div>
	        	</div>
	        	<div class="col-md-4">
	        		<div class="form-group">
	        			<label for="value" class="control-label">Value*</label>
                    	{!! Form::text('value', null, ['class'=>'form-control', 'id'=>'value']) !!}

                    	@if($errors->has('value'))
	                        <span class="invalid-feedback" role="alert">
	                            {{ $errors->first('value') }}
	                        </span>
	                    @endif
	        		</div>
	        	</div>
	        </div>
    		<div class="row">
	        	<div class="col-md-4">
	        		<div class="form-group">
	        			<label for="asset_id" class="control-label">Asset*</label>
                    	{!! Form::select('asset_id', ['' => '- Choose -']+$asset, null, ['class'=>'form-control select2', 'id'=>'asset_id']) !!}

                    	@if($errors->has('asset_id'))
	                        <span class="invalid-feedback" role="alert">
	                            {{ $errors->first('asset_id') }}
	                        </span>
	                    @endif	
	        		</div>
	        	</div>
	        	<div class="col-md-8" id="box-detail-asset">
	        		<div class="box box-solid bg-teal-gradient">
	        			<div class="box-header">
	        				<h3 class="box-title">Detail Asset</h3>
	        			</div>
	        			<div class="box-body" id="box-detail-text" style="display: none;">
	        				<table class="table table-bordered table-condensed">
	        					<tbody>
	        						<tr>
	        							<th width="30%">Owner Name</th>
	        							<td><span id="txt-owner-name"></span></td>
	        						<tr>
	        						</tr>
	        							<th>Price Market</th>
	        							<td><span id="txt-price-market"></span></td>
	        						</tr>
	        					</tbody>
	        				</table>
	        			</div>
	        		</div>
	        	</div>
	        </div>
	        <div class="row">
	        	<div class="col-md-4">
	        		<div class="form-group">
	        			<label for="time_of_use" class="control-label">Time of use*</label> <i>(Length of use)</i>
						<div class="input-group">
                    		{!! Form::text('time_of_use', null, ['class'=>'form-control', 'id'=>'time_of_use']) !!}
							<span class="input-group-addon">Days</span>
						</div>

                    	@if($errors->has('time_of_use'))
	                        <span class="invalid-feedback" role="alert">
	                            {{ $errors->first('time_of_use') }}
	                        </span>
	                    @endif	
	        		</div>
	        	</div>
	        	<div class="col-md-4">
	        		<div class="form-group">
	        			<label for="quota" class="control-label">Quota</label> <i>(Total investor)</i>
                    	{!! Form::number('quota', null, ['class'=>'form-control', 'id'=>'quota']) !!}

                    	@if($errors->has('quota'))
	                        <span class="invalid-feedback" role="alert">
	                            {{ $errors->first('quota') }}
	                        </span>
	                    @endif	
	        		</div>
	        	</div>
	        	<div class="col-md-4">
	        		<div class="form-group">
	        			<label for="min_invest_amount" class="control-label">Min. Invest Amount</label>
                    	{!! Form::number('min_invest_amount', null, ['class'=>'form-control', 'id'=>'min_invest_amount']) !!}

                    	@if($errors->has('min_invest_amount'))
	                        <span class="invalid-feedback" role="alert">
	                            {{ $errors->first('min_invest_amount') }}
	                        </span>
	                    @endif	
	        		</div>
	        	</div>
	        </div>
	        <div class="row">
	        	<div class="col-md-4">
	        		<div class="form-group">
	        			<label for="date_start" class="control-label">Date Start*</label>
	        			<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
                    		{!! Form::text('date_start', null, ['class'=>'form-control datepicker', 'id'=>'date_start']) !!}
						</div>

                    	@if($errors->has('date_start'))
	                        <span class="invalid-feedback" role="alert">
	                            {{ $errors->first('date_start') }}
	                        </span>
	                    @endif
	        		</div>
	        	</div>
	        	<div class="col-md-4">
	        		<div class="form-group">
	        			<label for="date_end" class="control-label">Date End*</label>
	        			<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
                    		{!! Form::text('date_end', null, ['class'=>'form-control datepicker', 'id'=>'date_end']) !!}
						</div>

                    	@if($errors->has('date_end'))
	                        <span class="invalid-feedback" role="alert">
	                            {{ $errors->first('date_end') }}
	                        </span>
	                    @endif
	        		</div>
	        	</div>
	        	{{-- <div class="col-md-4">
	        		<div class="form-group">
	        			<label for="status" class="control-label">Status*</label>
                    	{!! Form::select('status', [''=>'- Choose -', 'PUBLISHED'=>'Published', 'DRAFT'=>'Draft', 'CANCELED'=>'Canceled'], null, ['class'=>'form-control', 'id'=>'status']) !!}
						
                    	@if($errors->has('status'))
	                        <span class="invalid-feedback" role="alert">
	                            {{ $errors->first('status') }}
	                        </span>
	                    @endif
	        		</div>
	        	</div> --}}
	        	<div class="col-md-4">
	        		<div class="form-group">
	        			<label for="investors" class="control-label">Investors</label>
	        			<input type="file" name="investor" id="investor" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
						
                    	@if($errors->has('investor'))
	                        <span class="invalid-feedback" role="alert">
	                            {{ $errors->first('investor') }}
	                        </span>
	                    @endif
	        		</div>
	        	</div>
	        </div>
	        <div class="row">
	        	<div class="col-md-8">
	        		<div class="form-group">
	        			<label for="iframe" class="control-label">Video</label>
                    	{!! Form::textArea('iframe', null, ['class'=>'form-control', 'rows'=>2, 'id'=>'iframe']) !!}
						
                    	@if($errors->has('iframe'))
	                        <span class="invalid-feedback" role="alert">
	                            {{ $errors->first('iframe') }}
	                        </span>
	                    @endif
	        		</div>
	        	</div>
	        	<div class="col-md-4">
	        		<div class="form-group">
	        			<label for="image" class="control-label">Image</label>
	        			<input type="file" name="image" id="image" accept="image/x-png,image/gif,image/jpeg" />
						
                    	@if($errors->has('image'))
	                        <span class="invalid-feedback" role="alert">
	                            {{ $errors->first('image') }}
	                        </span>
	                    @endif
	        		</div>
	        	</div>
	        </div>
    		<div class="form-group">
    			<label for="desc" class="control-label">Description*</label>
            	{!! Form::textArea('desc', null, ['class'=>'form-control', 'id'=>'editor1']) !!}

            	@if($errors->has('desc'))
                    <span class="invalid-feedback" role="alert">
                        {{ $errors->first('desc') }}
                    </span>
                @endif
    		</div>
	        

	    </div>
    </div>
    <div class="box-footer">
    	<div class="box-body">
        	{!! Form::submit('Create', ['class'=>'btn btn-primary pull-right']) !!}
    	</div>
    </div>

    {!! Form::close() !!}
</div>
@endsection

@push('scripts')
<script type="text/javascript">
	$('#type').change(function(){
		if($(this).val() === 'PRIVATE'){
			$('#quota').val(0);
			document.getElementById("quota").disabled = true;
			document.getElementById("investor").disabled = false;
		}
		else{
			document.getElementById("quota").disabled = false;
			document.getElementById("investor").disabled = true;
		}
	});

	if($('#type').val() === 'PRIVATE')
    {
        document.getElementById("quota").disabled = true;
        document.getElementById("investor").disabled = false;
    }
    else
    {
        document.getElementById("quota").disabled = false;
        document.getElementById("investor").disabled = true;
    }

    $('#asset_id').change(function(){
    	let me = $(this);

    	if(me.val() != '')
    	{
    		$.ajax({
    			url : 'get-detail-asset/'+me.val(),
    			method: 'get',
    			dataType: 'json',
    			success:function(response){
    				$('#box-detail-text').show();
    				console.log(response.owner);
    				document.getElementById('txt-owner-name').innerHTML = response.owner;
    				document.getElementById('txt-price-market').innerHTML = response.price;
    			},
    			error:function(e){
    				alert('Failed to get asset detail');
    			}
    		});
    	} else {
    		$('#box-detail-text').hide();
    	}
    });
</script>
@endpush