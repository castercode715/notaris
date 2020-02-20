@extends('base.main')
@section('title') Voucher @endsection
@section('page_icon') <i class="fa fa-id-card"></i> @endsection
@section('page_title') Edit Voucher @endsection

@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
        	<a href="{{ route('vouchers.show', base64_encode($model->id) ) }}" class="btn btn-success" title="Show">
                <i class="fa fa-search"></i> Detail
            </a>
        	<a href="{{ route('vouchers.create') }}" class="btn btn-success" title="Create">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('vouchers.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => ['vouchers.update', base64_encode($model->id)],
        'method'=> 'put',
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
                {{-- <div class="col-md-4">
                    <div class="form-group">
                        <label for="status" class="control-label">Status*</label>
                        @if($model->status != 'PUBLISHED')
                            {!! Form::select('status', ['DRAFT'=>'Draft', 'CANCELED'=>'Canceled'], $model->status, ['class'=>'form-control', 'id'=>'status', 'required'=>'required']) !!}
                        @else
                            {!! Form::select('status', ['DRAFT'=>'Draft', 'CANCELED'=>'Canceled'], $model->status, ['class'=>'form-control', 'id'=>'status', 'required'=>'required', 'readonly']) !!}
                        @endif

                        @if($errors->has('status'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('status') }}
                            </span>
                        @endif
                    </div>
                </div> --}}
                <div class="col-md-4">
                    <div class="form-group">
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
                </div>
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
                        <div class="box-body" id="box-detail-text">
                            <table class="table table-bordered table-condensed">
                                <tbody>
                                    <tr>
                                        <th width="30%">Owner Name</th>
                                        <td><span id="txt-owner-name">{{ $asset_detail->owner_name}}</span></td>
                                    <tr>
                                    </tr>
                                        <th>Price Market</th>
                                        <td><span id="txt-price-market">{{ 'Rp '.number_format($asset_detail->price_market) }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
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
                <div class="col-md-3">
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
                <div class="col-md-3">
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
                <div class="col-md-3">
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
                <div class="col-md-3">
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
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="perbarui" class="control-label">Update data investor?</label>
                        <div>
                            <input type="checkbox" id="perbarui" name="perbarui" value="Y" onchange="check(this)" />
                        </div>
                    </div>
                </div>
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
                <div class="col-md-3">
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
                <div class="col-md-3">
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
            </div>
	    </div>
	</div>

    <div class="box-footer">
    	<div class="box-body">
        	{!! Form::submit('Save', ['class'=>'btn btn-primary pull-right btn-save']) !!}
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
            document.getElementById("perbarui").disabled = false;
            // document.getElementById("investor").disabled = false;
            // if($('#perbarui').checked == true && $('#investor').val() == '')
            //     $('.btn-save').prop('disabled', true);
        }
        else{
            document.getElementById("quota").disabled = false;
            document.getElementById("investor").disabled = true;
            document.getElementById("perbarui").checked = false;
            document.getElementById("perbarui").disabled = true;
        }
    });

    if($('#type').val() === 'PRIVATE')
    {
        document.getElementById("quota").disabled = true;
        if(document.getElementById("quota").checked == false)
            document.getElementById("investor").disabled = true;
        else
            document.getElementById("investor").disabled = false;
    }
    else
    {
        document.getElementById("quota").disabled = false;
        document.getElementById("investor").disabled = true;
    }

    function check(a)
    {
        if(a.checked){
            document.getElementById("investor").disabled = false;
        }
        else{
            document.getElementById("investor").disabled = true;
        }
    }

    $('#asset_id').change(function(){
        let me = $(this);

        if(me.val() != '')
        {
            $.ajax({
                url : '/master/vouchers/get-detail-asset/'+me.val(),
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