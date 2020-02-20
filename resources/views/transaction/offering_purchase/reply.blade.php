@extends('base.main')
@section('title') Offering Purchase @endsection
@section('page_icon') <i class="fa fa-envelope"></i> @endsection
@section('page_title') Offering Purchase @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('offering-purchase.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')

<div class="box box-primary box-solid">
	<div class="box-header">
		<h3 class="box-title"><i class="fa fa-envelope"></i> Message</h3>
	</div>
    <div class="box-body">
		<table class="table table-condensed">
			<tr>
				<th width="150px">From</th>
				<td>{{ $model->full_name }}</td>
			</tr>
			<tr>
				<th>Asset Name</th>
				<td>{{ $model->nama_asset }}</td>
			</tr>
			<tr>
				<th>Send Date</th>
				<td>{{ date('d M Y H:i', strtotime($model->created_at)).' WIB' }}</td>
			</tr>
			<tr>
				<th>Message</th>
				<td>
					<p>{!! $model->message !!}</p>
				</td>
			</tr>
			<tr>
				<th>Attachment</th>
				<td>
					@if($model->file)
					<ul class="mailbox-attachments clearfix">
						<li>
		                  	<span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"></i></span>

		                  	<div class="mailbox-attachment-info">
		                    	<a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i>{{ $model->file }}</a>
		                        <span class="mailbox-attachment-size">
		                        	&nbsp;
		                          	<a href="/files/inbox/{{ $model->file }}" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
		                        </span>
		                  	</div>
		                </li>
					</ul>
					@else
					<i>None</i>
					@endif
				</td>
			</tr>
		</table>
		<div class="form-group">
			<div class="pull-right">
				<button type="button" class="btn btn-default btn-reply"><i class="fa fa-reply"></i> Reply</button>
			</div>
		</div>
		<!-- lokasi form -->
		<div class="form-reply" style="display: none;">
			{!! Form::open(['route'=>'offering-purchase.store','method'=>'post','enctype'=>'multipart/form-data']) !!}
			
			@if(!$model->parent_id)
				{!! Form::hidden('parent_id', $model->id, []) !!}
			@else
				{!! Form::hidden('parent_id', $model->parent_id, []) !!}
			@endif
			
			{!! Form::hidden('id', $model->id, []) !!}
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						{!! Form::textarea('message', null, ['class'=>'form-control', 'rows'=>'4', 'id'=>'editor1']) !!}

						@if($errors->has('message'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('message') }}
                            </span>
                        @endif
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<div class="btn btn-default btn-file">
		                  	<i class="fa fa-paperclip"></i> Attachment
		                  	<input type="file" name="attachment">
		                </div>
	                </div>
				</div>
				<div class="col-md-12">
					<div class="form-group pull-right">
						<button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Send</button>
	                </div>
				</div>
			</div>
			{!! Form::close() !!}
		</div>
    </div>
</div>
<div class="box box-warning box-solid">
	<div class="box-header">
		<h3 class="box-title"><i class="fa fa-reply"></i> Previous Message</h3>
	</div>
    <div class="box-body">
		<table class="table table-striped table-bordered">
			<tbody>
				@foreach($data as $r)
				<tr>
					<td width="20%">
						<p>
							<b>
								@if($r->investor_id)
									{{ $uti->investor($r->investor_id) }}
								@else
									{{ $uti->employee($r->created_by) }}
								@endif		
							</b><br>
							{{ date('d/m/Y H:i', strtotime($r->created_at)).' WIB' }}
						</p>
					</td>
					<td>
						<p>{!! $r->message !!}</p>
						@if($r->file)
						<ul class="mailbox-attachments clearfix">
    						<li>
			                  	<span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"></i></span>

			                  	<div class="mailbox-attachment-info">
			                    	<a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i>{{ $r->file }}</a>
			                        <span class="mailbox-attachment-size">
			                        	&nbsp;
			                          	<a href="/files/inbox/{{ $r->file }}" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
			                        </span>
			                  	</div>
			                </li>
    					</ul>
						@endif
					</td>
				</tr>
				@endforeach

				@if($parent)
				<tr>
					<td width="20%">
						<p>
							<b>
									{{ $parent->investor->full_name }}
							</b><br>
							{{ date('d/m/Y H:i', strtotime($parent->created_at)).' WIB' }}
						</p>
					</td>
					<td>
						<p>{!! $parent->message !!}</p>
						@if($parent->file)
						<ul class="mailbox-attachments clearfix">
    						<li>
			                  	<span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"></i></span>

			                  	<div class="mailbox-attachment-info">
			                    	<a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i>{{ $parent->file }}</a>
			                        <span class="mailbox-attachment-size">
			                        	&nbsp;
			                          	<a href="/files/inbox/{{ $parent->file }}" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
			                        </span>
			                  	</div>
			                </li>
    					</ul>
						@endif
					</td>
				</tr>
				@endif

			</tbody>
		</table>
    </div>
</div>

@endsection

@push('scripts')
<script>
	$('.btn-reply').click(function(){
		if($('.form-reply').is(':hidden'))
			$('.form-reply').show();
		else
			$('.form-reply').hide();
	});
</script>
@endpush