@extends('base.main')
@section('title') Product Ecommerce Create @endsection
@section('page_icon') <i class="fa fa-opencart"></i> @endsection
@section('page_title') Product Ecommerce @endsection
@section('page_subtitle') Create @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('product.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
 
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => 'product.store',
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
                
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name" class="control-label">Product Name*</label>
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
                        <label for="desc" class="control-label">Description*</label>
                        <textarea name="desc" class="form-control" id="editor1">{{ old('desc') }}</textarea>

                        @if($errors->has('desc'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('desc') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="term_conds" class="control-label">Term Conds*</label>
                        <textarea name="term_conds" class="form-control" id="editor2">{{ old('term_conds') }}</textarea>

                        @if($errors->has('term_conds'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('term_conds') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="price" class="control-label">Price*</label>
                        <input type="text" placeholder="IDR" name="price" id="price" value="{{ old('price') }}" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="discount" class="control-label">Discount</label>
                        <input type="text" placeholder="%" name="discount" id="discount" value="{{ old('discount') }}" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tenor" class="control-label">Tenor*</label>
                         {!! Form::select('tenor[]', $tenor, null, ['class'=>'form-control select2','multiple'=>'multiple']) !!}
                    </div>
                </div>

                 <div class="col-md-3">
                    <div class="form-group">
                        <label for="status" class="control-label">Category*</label>
                        <div id="treeview-checkbox-demo">
                            <ul>
                                @foreach($categorys as $key)
                                    <li data-value="{{ $key->id }}">{{ $key->name }}

                                    @if(count($model->getchildCategory($key->id)))
                                        @include('master.ecommerce.manageChild',['childs' => $model->getchildCategory($key->id)])
                                    @endif
                                @endforeach
                            
                            </ul>
                        </div>
                       
                        <input type="hidden" name="values" id="values">
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <fieldset>
                            <legend>Attribute Product:</legend>
                            <div class="attributes_wrapper">
                            <div class="col-md-3">
                                <label for="images" class="control-label">Name</label>
                                
                                {!! Form::select('attr_name[]', [''=>'- Select -'] + $attribute, null, ['class'=>'form-control select', 'id'=>'attr_name[]', 'required'=>'required']) !!}
                            </div>

                            <div class="col-md-7">
                                <label for="images" class="control-label">Value</label>
                                
                                <input type="text" name="attr_value[]" class="form-control">
                            </div>

                            <div class="col-md-2">
                                <a href="javascript:void(0);" class="add_attr_button btn btn-sm btn-success" title="Add Field" style="margin-top: 25px;"><i class="fa fa-plus"></i></a>
                            </div>
                            </div>


                        </fieldset>
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
                </div>

               

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status" class="control-label">Status*</label>
                             {!! Form::select('status', [''=>'- Select -']+['ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE'], null, ['class'=>'form-control', 'id'=>'status', 'required'=>'required']) !!}
                        </div>
                    </div>

                
                </div>

            </div>

            
            <div class="row">
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="asset_name" class="control-label">Featured Image</label>
                        <div class="dropzone" id="apake2"></div>
                        <input type="hidden" name="featured" id="featured"></input>
                    </div>
                </div>

                <div class="col-md-6">
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
        {!! Form::submit('Create Product', ['class'=>'btn btn-primary pull-right']) !!}
    </div>
    {!! Form::close() !!}
</div>










@endsection

@push('scripts')
  @include('master.ecommerce.script')
@endpush