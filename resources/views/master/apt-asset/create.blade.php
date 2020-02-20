@extends('base.main')
@section('title') APT Asset Create @endsection
@section('page_icon') <i class="fa fa-cubes"></i> @endsection
@section('page_title') APT Asset Create  @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('apt-asset.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => 'apt-asset.store',
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
                        <label for="asset_name" class="control-label">Code APT*</label>
                        <input type="text" class="form-control" name="code_apt" value="{{ old('code_apt') }}">
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
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="price" class="control-label">Price*</label>
                        <input type="text" name="price" id="price" value="{{ old('price') }}" class="form-control">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tenor" class="control-label">Tenor*</label>
                        <input type="text" name="tenor" id="tenor" value="{{ old('tenor') }}" class="form-control">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="installment" class="control-label">Installment*</label>
                        <input type="text" name="installment" id="installment" value="{{ old('installment') }}" class="form-control">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="maintenance" class="control-label">Maintenance*</label>
                        <input type="text" name="maintenance" id="maintenance" value="{{ old('maintenance') }}" class="form-control">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="type_apt" class="control-label">Type APT*</label>
                         {!! Form::select('type_apt', [''=>'- Select -']+['STUDIO 21'=>'STUDIO 21','STUDIO 24'=>'STUDIO 24'], null, ['class'=>'form-control', 'id'=>'type_apt', 'required'=>'required']) !!}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status" class="control-label">Status*</label>
                         {!! Form::select('status', [''=>'- Select -']+['ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE'], null, ['class'=>'form-control', 'id'=>'status', 'required'=>'required']) !!}
                    </div>
                </div>
               
            </div>

            <div class="row">
                
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="asset_name" class="control-label">Berkas/file</label>
                        <div class="dropzone" id="apake3"></div>
                        <input type="hidden" name="file" id="file"></input>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="asset_name" class="control-label">Featured Image</label>
                        <div class="dropzone" id="apake2"></div>
                        <input type="hidden" name="featured" id="featured"></input>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="asset_name" class="control-label">Another Image</label>
                        <div class="dropzone" id="apake"></div>
                        <input type="hidden" name="bebasNo" value="1" id="bebasNo">
                        <div id="terserah"></div>
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
  @include('master.apt-asset.script')
@endpush