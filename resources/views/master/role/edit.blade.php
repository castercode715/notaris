@extends('base.main')
@section('title') Role @endsection
@section('page_icon') <i class="fa fa-book"></i> @endsection
@section('page_title') Role @endsection
@section('page_subtitle') create @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('role.index') }}" class="btn btn-default"><i class="fa fa-list"></i> Manage</a>
        </div>
    </div>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="box box-solid">


        @foreach($data as $datas)
            <form action="{{ route('role.update', $datas->id) }}" method="post">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="box-body">
                    <div class="form-group">
                        <label for="nama">Nama<span class="required">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control" value="{{ $datas->nama }}">
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="active" id="active" value="{{ $datas->active }}"> Active
                        </label>
                    </div>

                    <div class="form-group">
                        {{--<label for="created_by">Created by<span class="required">*</span></label>--}}
                        <input type="hidden" name="created_by" id="created_by" class="form-control"
                               value="{{ $datas->created_by }}" >
                    </div>
                    <div class="form-group">
                        {{--    <label for="created_by">Updated by<span class="required">*</span></label>--}}
                        <input type="hidden" name="updated_by" id="updated_by" class="form-control"
                               value="{{ $datas->updated_by }}">
                    </div>
                </div>
                <div class="box-footer" style="text-align:right;">
                    <input type="submit" value="Save" class="btn btn-danger">
                </div>
            </form>
        @endforeach
    </div>
@endsection