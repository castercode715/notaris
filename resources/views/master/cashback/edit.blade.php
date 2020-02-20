@extends('base.main')
@section('title') Edit Voucher Cashback #{{ $model->id }} @endsection
@section('page_icon') <i class="fa fa-money"></i> @endsection
@section('page_title') Edit Voucher Cashback #{{ $model->id }} @endsection
@section('page_subtitle') edit @endsection
@section('menu')
<div class="box box-solid" style="text-align:right;">
    <div class="box-body">
        <a href="{{ route('cashback.show', $model->id) }}" class="btn btn-success" title="View Voucher Cashback">
            <i class="fa fa-search"></i> Show
        </a>
        <a href="{{ route('cashback.delete', $model->id) }}" class="btn btn-danger" title="Delete Voucher Cashback">
            <i class="fa fa-eraser"></i> Delete
        </a>
        <a href="{{ route('cashback.create') }}" class="btn btn-success" title="Create Voucher Cashback">
            <i class="fa fa-plus"></i> Create
        </a>
        <a href="{{ route('cashback.index') }}" class="btn btn-success" title="Manage Voucher Cashback">
            <i class="fa fa-list"></i> Manage
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => ['cashback.update', $model->id],
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
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="title" class="control-label">Title*</label>
                        {!! Form::text('title', $model->redeemInd()->title, ['class'=>'form-control input-lg', 'id'=>'title', 'readonly'=>'readonly']) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="amount" class="control-label">Cashback Amount*</label>
                        {!! Form::number('amount', $model->amount, ['class'=>'form-control @error is-invalid @enderror',
                        'id'=>'amount']) !!}
                        @if($errors->has('amount'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('amount') }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="quota" class="control-label">Quota*</label>
                        {!! Form::number('quota', $model->quota, ['class'=>'form-control', 'id'=>'quota']) !!}
                        @if($errors->has('quota'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('quota') }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="type" class="control-label">Type*</label>
                        @if ($model->type == 'PUBLIC')
                        {!! Form::select('type', ['' => '- Choose -', 'PUBLIC'=>'PUBLIC', 'PRIVATE'=>'PRIVATE'], $model->type, ['class'=>'form-control', 'id'=>'type', 'readonly'=>'readonly']) !!}    
                        @else
                        {!! Form::select('type', ['' => '- Choose -', 'PUBLIC'=>'PUBLIC', 'PRIVATE'=>'PRIVATE'], $model->type, ['class'=>'form-control', 'id'=>'type']) !!}
                        @endif
                        

                        @if($errors->has('type'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('type') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="date_start" class="control-label">Date Start*</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            {!! Form::text('date_start', date('d-m-Y', strtotime($model->date_start)), ['class'=>'form-control datepicker',
                            'id'=>'date_start'])
                            !!}
                        </div>

                        @if($errors->has('date_start'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('date_start') }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="date_end" class="control-label">Date End*</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            {!! Form::text('date_end', date('d-m-Y', strtotime($model->date_end)), ['class'=>'form-control datepicker', 'id'=>'date_end'])
                            !!}
                        </div>

                        @if($errors->has('date_end'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('date_end') }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="investor" class="control-label">Investor</label>
                        <p>*Choose if you want to replace</p>
                        <input type="file" name="investor" id="investor"
                            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                        @if($errors->has('investor'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('investor') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <h3>Term & Conditions*</h3>
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-8">
                        <select name="term_cond" id="term_cond" class="form-control">
                            <option value="">Choose</option>
                            @foreach ($term as $item)
                            <option value="{{ $item->code }}" data-min_amount="{{ $item->min_amount }}"
                                data-min_tenor="{{ $item->min_tenor}}" data-start="{{ $item->date_start }}"
                                data-end="{{ $item->date_end }}">{{ $item->label }}</option>
                            @endforeach
                        </select>

                        @if($errors->has('tc_code'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('tc_code') }}
                        </span>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <button type="button" class="btn btn-primary" id="btn-add-tc">Add</button>
                        <button type="button" class="btn btn-success" id="btn-form-tc">New</button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <table class="table table-bordered table-striped" id="table-tc">
                    <thead>
                        <tr>
                            <th width="50px">No.</th>
                            <th>Code</th>
                            <th>Label</th>
                            <th>Min. Amount</th>
                            <th>Min. Tenor</th>
                            <th>Range Date</th>
                            <th width="60px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (empty($myterm))
                            <tr class="empty">
                                <td colspan="6" style="text-align:center;">Data not found</td>
                            </tr>
                        @else
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($myterm as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        {{ $item->redeemTermCond->code }}
                                        <input type="hidden" name="tc_code[]" value="{{ $item->redeemTermCond->code }}" />
                                    </td>
                                    <td>{{ $item->redeemTermCond->label }}</td>
                                    <td>{!! number_format($item->redeemTermCond->min_amount, 0, ',', '.')  !!}</td>
                                    <td>{!! $item->redeemTermCond->min_tenor.' Days'  !!}</td>
                                    <td>{!! date('d/m/Y', strtotime($item->redeemTermCond->date_start)).' - '.date('d/m/Y', strtotime($item->redeemTermCond->date_end)) !!}</td>
                                    <td><button class="btn btn-sm btn-danger btn-remove-tc"><i class="fa fa-close"></i></button></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <div class="box-footer">
        <div class="box-body">
            {!! Form::submit('Save', ['class'=>'btn btn-lg btn-primary pull-right']) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>

<div class="modal fade" id="modal-form-create-cashback-tc" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Create New Voucher Cashback's Term & Conditions</h4>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <form action="{{ route('cashback.tc.create') }}" method="post" id="form-create-tc">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="type">Type*</label>
                                    <select name="tc_type" id="tc_type" class="form-control" autocomplete="off">
                                        <option value="">Choose</option>
                                        <option value="TOPUP_">TOPUP</option>
                                        <option value="INVESTMENT_">INVESTMENT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="code">Code*</label>
                                    <input type="text" name="tc_coder" id="tc_coder" class="form-control" maxlength="16"
                                        autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="code"></label>
                                    <input type="text" name="tc_type_code" id="tc_type_code" class="form-control"
                                        maxlength="16" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="label">Label*</label>
                                    <input type="text" name="tc_label" id="tc_label" class="form-control"
                                        maxlength="100" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="min_amount">Minimal Amount</label>
                                        <input type="number" name="tc_min_amount" id="tc_min_amount"
                                            class="form-control" autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="min_tenor">Minimal Tenor</label>
                                        <input type="number" name="tc_min_tenor" id="tc_min_tenor" class="form-control"
                                            autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="date_start">Date Start</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" name="tc_date_start" id="tc_date_start"
                                                class="form-control datepicker" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="date_end">Date End</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" name="tc_date_end" id="tc_date_end"
                                                class="form-control datepicker" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-create-tc">Create and Add</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('body').on('click','#btn-form-tc',function(e){
    e.preventDefault();

    $('#modal-form-create-cashback-tc').modal('show');
});

let x = 1;

$('body').on('click','#btn-create-tc', function(e){
    e.preventDefault();

    let form = $('#form-create-tc'),
        url = form.attr('action'),
        method = form.attr('method');

    form.find('.help-block').remove();
    form.find('.form-group').removeClass('has-error');

    $.ajax({
        url: url,
        method : method,
        data : form.serialize(),
        dataType: 'json',
        success: function(response){
            console.log(response);
            let option = new Option(response.label, response.code);
            $(option).html(response.label);
            $('#term_cond').append(option);
            $('#table-tc tbody').children('.empty').remove();
            $('#table-tc tbody').append('<tr>'
                +'<td>'+x+'.</td>'
                +'<td>'
                    +'<input type="hidden" name="tc_code[]" value="'+response.code+'" />'
                    +response.code
                +'</td>'
                +'<td>'+response.label+'</td>'
                +'<td>'+response.min_amount+'</td>'
                +'<td>'+response.min_tenor+'</td>'
                +'<td>'+response.date_start+' - '+response.date_end+'</td>'
                +'<td>'
                    +'<button class="btn btn-sm btn-danger btn-remove-tc"><i class="fa fa-close"></i></button>'
                +'</td>'
            +'</tr>');
            $('#modal-form-create-cashback-tc').modal('hide');
            x++;
        },
        error: function(e){
            console.log('error : '+e);
            var response = e.responseJSON;
            // console.log(response);
            if($.isEmptyObject(response) == false)
            {
                $.each(response.errors, function(key, value) {
                    $('#' + key)
                        .closest('.form-group')
                        .addClass('has-error')
                        .append('<span class="help-block">'+ value +'</span>')
                });
            }
        }
    });
});

$('body').on('click','.btn-remove-tc',function(e){
    e.preventDefault();
    $(this).closest('tr').remove();
    x--;
});

$('#type').change(function(){
    if($(this).val() === 'PRIVATE'){
        $('#quota').val(0);
        document.getElementById("investor").disabled = false;
    }
    else{
        document.getElementById("investor").disabled = true;
    }
});

$('body').on('change', '#tc_type', function(){
    $('#tc_coder').val('');
    $('#tc_type_code').val($(this).val());
});

$('body').on('change', '#tc_coder', function(){
    let value = $('#tc_type').val() + $(this).val();
    $('#tc_type_code').val(value);
});

$('body').on('click','#btn-add-tc',function(e){
    e.preventDefault();
    let select = $('#term_cond'),
        value = select.val(),
        label = select.find(':selected').text(),
        min_amount = select.find(':selected').data('min_amount'),
        min_tenor = select.find(':selected').data('min_tenor'),
        start = select.find(':selected').data('start'),
        end = select.find(':selected').data('end');
    
    $('#table-tc tbody').children('.empty').remove();
    $('#table-tc tbody').append('<tr>'
        +'<td>'+x+'.</td>'
        +'<td>'
            +'<input type="hidden" name="tc_code[]" value="'+value+'" />'
            +value
        +'</td>'
        +'<td>'+label+'</td>'
        +'<td>'+min_amount+'</td>'
        +'<td>'+min_tenor+'</td>'
        +'<td>'+start+' - '+end+'</td>'
        +'<td>'
            +'<button class="btn btn-sm btn-danger btn-remove-tc"><i class="fa fa-close"></i></button>'
        +'</td>'
    +'</tr>');

    x++;
});
</script>
@endpush