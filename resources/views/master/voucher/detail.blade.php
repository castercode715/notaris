@extends('base.main')
@section('title') Voucher @endsection
@section('page_icon') <i class="fa fa-id-card"></i> @endsection
@section('page_title') Detail Voucher @endsection

@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
           
            <a href="{{ route('voucher.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => ['voucher.update-satu', $model->id],
        'method'=> 'put',
    ]) !!}
    <div class="box-body">
        @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{  $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h3 style="margin-top: 2px;"><i class="fa fa-language"></i> Voucher Detail</h3>
        <input type="hidden" name='code' value="{{ $model->code }}">


        <hr style="margin-top: -1px;">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="category_asset_id" class="control-label">Asset Name*</label>
                    {!! Form::select('asset_id', [''=>'- Select -'] + $page, null, ['class'=>'form-control select2 dynamic', 'id'=>'asset_id']) !!}

                    @if($errors->has('asset_id'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('asset_id') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                    <div class="form-group">
                        <label for="date_available" class="control-label">Available Date*</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="date_start" type="text" class="form-control pull-right" value="{{ date('d-m-Y', strtotime($model->date_start)) }}" id="datepicker">
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
                    <label for="title" class="control-label">Number Interest*</label>
                    {!! Form::text('number_interest', null, ['class'=>'form-control', 'id'=>'number_interest']) !!}

                    @if($errors->has('number_interest'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('number_interest') }}
                        </span>
                    @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                    <label for="title" class="control-label">Long Promo*</label>
                    {!! Form::text('long_promo', null, ['class'=>'form-control', 'id'=>'long_promo']) !!}

                    @if($errors->has('long_promo'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('long_promo') }}
                        </span>
                    @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date_expired" class="control-label">Expired Date*</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="date_expired" type="text" class="form-control pull-right" value="{{ date('d-m-Y', strtotime($model->date_end)) }}" id="datepicker2">
                        </div>

                        @if($errors->has('date_expired'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('date_expired') }}
                            </span>
                        @endif
                    </div>
                </div>
                

                <div class="col-md-4">
                    <div class="form-group">
                    <label for="title" class="control-label">Quota*</label>
                    {!! Form::text('quota', null, ['class'=>'form-control', 'id'=>'quota']) !!}

                    @if($errors->has('quota'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('quota') }}
                        </span>
                    @endif
                    </div>
                </div>

                <div class="col-md-4">
                <div class="form-group">
                    <label for="gender" class="control-label">Status*</label>
                    {!! Form::select('status', [''=>'- Select -']+['Active'=>'Active','Inactive'=>'Inactive'], null, ['class'=>'form-control', 'id'=>'status']) !!}

                    @if($errors->has('status'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('status') }}
                        </span>
                    @endif
                </div>
            </div>

        </div>

        <div class="row">
            
        </div>
    </div>
    <div class="box-footer">
        {!! Form::submit('Update', ['class'=>'btn btn-primary pull-right']) !!}
    </div>
    {!! Form::close() !!}
</div>

   <div class="row">
        <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-language"></i> Collapsible Language</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                @php $no = 1; @endphp
               @foreach($lang as $status)
                <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse@php echo $no; @endphp" aria-expanded="false" class="collapsed">
                        <i class="fa fa-language"></i> {{ $status->language }}


                      </a>
                    </h4>
                  </div>
                  <div id="collapse@php echo $no; @endphp" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="box-body">
                     
                        <div class="row">
                             <div class="col-md-12">
                                <div class="form-group">
                                    <label for="iframe" class="control-label">{{ $status->title }}</label>
                                    
                                    <p>{!! $status->desc !!}</p>

                                    
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="iframe" class="control-label">Image</label>
                                    
                                   <p><img width='300' height="200" src="/images/voucher/{{ $status->image }}" class="img-thumbnail"></p>    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="iframe" class="control-label">Iframe</label>
                                    
                                   <p><textarea class="form-control" rows="4">{{ $status->iframe }}</textarea></p>    
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="{{ route('voucher.show-detail', $status->no) }}" class="btn btn-primary pull-right">Edit</a>
                            
                        </div>


                    </div>
                  </div>
                </div>
                @php $no++; @endphp
                @endforeach
              </div>
              
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        
        <!-- /.col -->
      </div>
@endsection
