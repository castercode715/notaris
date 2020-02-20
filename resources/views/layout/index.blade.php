@extends('base.main')
@section('title') Layout @endsection
@section('page_icon') <i class="fa fa-book"></i> @endsection
@section('page_title') Layout @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            {{--<a href="{{ route('layout.create') }}" class="btn btn-default"><i class="fa fa-plus"></i> Create</a>--}}
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-create">
                <i class="fa fa-plus"></i> Add
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="box box-solid">
        <div class="box-body">
            <table class="table table-bordered" id="table2">
                <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Created Date</th>
                    <th class="text-center">Created By</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @if (count($data))
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->created_by }}</td>
                            <td>
                                <a href="{{ route('layout.edit', $item->id) }}"><i class="fa fa-edit"></i></a>
                                <a href="{{ route('layout.destroy', $item->id) }}" data-method="delete" class="jquery-postback"><i class="fa fa-eraser"></i></a>
                                <form action="{{ url('/layout', ['id' => $item->id]) }}" method="post">
                                    <input type="hidden" name="_method" value="delete" />
                                    {!! csrf_field() !!}
                                    <input type="submit" value="delete" >
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No data :(</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection




<div class="modal fade" id="modal-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Layout</h4>
            </div>
            <div class="modal-body">
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
                    <form action="{{ route('layout.store') }}" method="post">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nama">Nama<span class="required">*</span></label>
                                <input type="text" name="nama" id="nama" class="form-control">
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="active" id="active" value="0"> Active
                                </label>
                            </div>
                            <div class="form-group">
                                {{--<label for="created_by">Created by<span class="required">*</span></label>--}}
                                <input type="hidden" name="created_by" id="created_by" class="form-control" value="{{ $user->id }}">
                            </div>
                            <div class="form-group">
                                {{--<label for="created_by">Updated by<span class="required">*</span></label>--}}
                                <input type="hidden" name="updated_by" id="updated_by" class="form-control" value="{{ $user->id }}">
                            </div>
                        </div>
                        <div class="box-footer" style="text-align:right;">

                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            <input type="submit" value="Save changes" class="btn btn btn-danger">

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->



