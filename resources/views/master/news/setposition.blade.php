@extends('base.main')
@section('title') News @endsection
@section('page_icon') <i class="fa fa-image"></i> @endsection
@section('page_title') Set News Position @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('banner.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
	<div class="box-body">
		
	</div>
</div>
@endsection