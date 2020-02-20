@extends('base.main')
@section('title') Village @endsection
@section('page_icon') <i class="fa fa-book"></i> @endsection
@section('page_title') Village @endsection
@section('page_subtitle') edit @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('village.index') }}" class="btn btn-success"><i class="fa fa-list"></i> Manage</a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
{!! Form::model($model, [
    'route' => ['village.update', $model->id],
    'method'=> 'put'
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
                    <select name="districts_id" id="mst_districts" class="form-control dynamic" data-table="mst_villages" data-key="districts_id">
                        <option value="">- Select -</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}" {{ $address['district_id']==$district->id?'selected':'' }}>{{ $district->name }}</option>
                        @endforeach
                    </select>

                    @if($errors->has('districts_id'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('districts_id') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-2"> 
                <div class="form-group">
                    <label for="id" class="control-label">ID*</label>
                    {!! Form::text('id', null, ['class'=>'form-control', 'id'=>'id','readonly']) !!}

                    @if($errors->has('id'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('id') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-4"> 
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