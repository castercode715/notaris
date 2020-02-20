@extends('base.main')
@section('title')KPR Asset Create @endsection
@section('page_icon') <i class="fa fa-folder"></i> @endsection
@section('page_title')About Us  @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('asset.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => 'asset.store',
        'method'=> 'post',
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

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="asset_name" class="control-label">Code</label>
                        <input type="text" name="bahasa" value="{{ $uti->SquanceCode() }}" disabled="disabled" class="form-control">
                        <input type="hidden" name="code" value="{{ $uti->SquanceCode() }}">
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="title" class="control-label">Asset Name*</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                        
                        @if($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('name') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="description" class="control-label">Description*</label>
                        <textarea name="description" class="form-control" id="editor1">{{ old('description') }}</textarea>

                        @if($errors->has('description'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('description') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="term_cond" class="control-label">Term Conds*</label>
                        <textarea name="term_cond" class="form-control" id="editor2">{{ old('term_cond') }}</textarea>

                        @if($errors->has('term_cond'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('term_cond') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="location" class="control-label">Location</label>
                    <hr>
                    <div class="form-group">
                        
                        <div class="col-md-4"> 
                            <div class="form-group">
                                <label for="country" class="control-label">Country</label>
                                <select name="country" id="country" class="form-control select2 dynamic" data-table="mst_provinces" data-key="countries_id">
                                    <option value="">- Select -</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
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
                                {{-- <select name="province" id="mst_provinces" class="form-control select2 dynamic" data-table="mst_regencies" data-key="provinces_id" required="required">
                                    <option value="">- Select -</option>
                                </select> --}}
                                <select name="province" id="mst_provinces" class="form-control select2 dynamic" data-table="mst_regencies" data-key="provinces_id">
                                    <option value="">- Select -</option>
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
                                {{-- <select name="regencies_id" id="mst_regencies" class="form-control select2" required="required">
                                    <option value="">- Select -</option>
                                </select> --}}
                                <select name="regencies_id" id="mst_regencies" class="form-control select2">
                                    <option value="">- Select -</option>
                                </select>

                                @if($errors->has('regencies_id'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('regencies_id') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="asset_name" class="control-label">Featured Image</label>
                        <div class="dropzone" id="apake2"></div>
                        <input type="hidden" name="featured" id="featured"></input>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="asset_name" class="control-label">Image</label>
                        <div class="dropzone" id="apake"></div>
                        <input type="hidden" name="bebasNo" value="1" id="bebasNo">
                        <div id="terserah"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="installment" class="control-label">Installment</label>
                        <input type="text" name="installment" id="installment" value="{{ old('installment') }}" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="price" class="control-label">Price</label>
                        <input type="text" name="price" id="price" value="{{ old('price') }}" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tenor" class="control-label">Tenor</label>
                        <input type="text" name="tenor" id="tenor" value="{{ old('tenor') }}" class="form-control">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status" class="control-label">Status</label>
                        {!! Form::select('status', [''=>'- Select -']+['D'=>'Draft','P'=>'Publish'], null, ['class'=>'form-control', 'id'=>'status', 'required'=>'required']) !!}
                    </div>
                </div>
               
            </div>

            
            
        </div>
    </div>
    <div class="box-footer">
        {!! Form::submit('Create', ['class'=>'btn btn-primary pull-right']) !!}
    </div>
    {!! Form::close() !!}
</div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){

            $("#tenor").keypress(function (e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });

            // Format mata uang.
            $( '#price' ).mask('000,000,000,000', {reverse: true});
            $( '#installment' ).mask('000,000,000,000', {reverse: true});

        })

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

    <script type="text/javascript">
        Dropzone.options.apake2 = false;
        var apake2 = new Dropzone('#apake2', {
          url: '{{ route('kpr.asset.uploadFeatured') }}',
          params: { _token: $('meta[name="csrf-token"]').attr('content') },
          paramName: 'img',
          acceptedFiles: 'image/*',
          maxFiles: 1,
          maxFilesize: 128,
          addRemoveLinks: true,
          parallelUploads: 1
        });

        apake2.on('success', function(file, data) {
          $('#featured').val(data);
        });

        apake2.on('removedfile', function(file) {
          $('#featured').val('');
        });
    </script>

    <script type="text/javascript">
        Dropzone.options.apake = false;

        var apake = new Dropzone('#apake', {
            url: '{{ route('kpr.asset.upload') }}',
            params: { _token: $('meta[name="csrf-token"]').attr('content') },
            paramName: 'img',
            acceptedFiles: 'image/*',
            maxFiles: 7,
            maxFilesize: 128,
            addRemoveLinks: true,
            parallelUploads: 2
        });

        apake.on('success', function(file, data) {
            file.bebas = $('#bebasNo').val();
            $('#terserah').append('<input type="hidden" id="img_' + $('#bebasNo').val() + '" name="image[]" value="' + data + '">');
            $('#bebasNo').val(parseInt($('#bebasNo').val()) + 1);
        });

        apake.on('removedfile', function(file) {
            $('#img_' + file.bebas).remove();
        });
    </script>
@endpush
