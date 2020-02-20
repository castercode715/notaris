<div class="box box-solid">
	<div class="box-body">
		<!-- start accordion -->
		<div class="box-group" id="accordion">
			<div class="panel box box-solid">
				<div class="box-header with-border">
					<h4 class="box-title"><strong>
						<a href="#collapse1" data-toggle="collapse" data-parent="#accordion">Indonesia</a>
					</strong></h4>
					<div class="box-tools pull-right">
						<a href="{{ route('news.edit-new', [base64_encode($model->news_lang_id)]) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
					</div>
				</div>
				<div id="collapse1" class="panel-collapse collapse in">
					<div class="box-body">
						<h3><strong>{{ $model->title }}</strong></h3>
						<h5><i><strong>{{ $model->sub_title }}</strong></i></h5>
						{!! html_entity_decode($model->description) !!}
						<hr>
						<div class="row">
							<div class="col-md-6">
								@if($model->image != '')
								<img src="/images/news/{{ $model->image }}" class="img-responsive">
								@endif
							</div>
							<div class="col-md-6">
								{!! $model->iframe !!}
							</div>
						</div>
					</div>
				</div>
			</div>
			@php $no = 2; @endphp
			@foreach($language as $l)
			@php 
			$data = $news->getData($l->code);
			@endphp
			<div class="panel box box-solid">
				<div class="box-header with-border">
					<h4 class="box-title"><strong>
						<a href="#collapse{{ $no }}" data-toggle="collapse" data-parent="#accordion">{!! ucwords(strtolower($l->language)) !!}</a>
					</strong></h4>
					@if($data)
					<div class="box-tools pull-right">
						<a href="{{ route('news.edit-new', [base64_encode($data->id)]) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
					</div>
					@endif
				</div>
				<div id="collapse{{ $no }}" class="panel-collapse collapse">
					<div class="box-body">
						@if($data)
						<h3><strong>{{ $data->title }}</strong></h3>
						<h5><i><strong>{{ $data->sub_title }}</strong></i></h5>
						{!! html_entity_decode($data->description) !!}
						<hr>
						<div class="row">
							<div class="col-md-6">
								@if($data->image != '')
								<img src="/images/news/{{ $data->image }}" class="img-responsive">
								@endif
							</div>
							<div class="col-md-6">
								{!! $data->iframe !!}
							</div>
						</div>
						@else
						<p align="center">Please add description using this language</p>
						<p align="center"><a href="{{ route('news.create-new',[ base64_encode($model->id), $l->code]) }}" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> Add Description</a></p>
						@endif
					</div>
				</div>
			</div>
			@php $no++; @endphp
			@endforeach
		</div>
		<!-- end accordion -->
	</div>
	<div class="box-footer">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-list"></i> Detail</h3>
			</div>
			<div class="box-body">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<th width="20%">Active</th>
							<td>
								@if($model->active == 1)
									<span class="badge bg-blue">ACTIVE</span>
								@else
									<span class="badge bg-red">INACTIVE</span>
								@endif
							</td>
						</tr>
						<tr>
							<th>Category</th>
							<td>
								@php
									$last = count($category);
									$no = 1;
								@endphp
								@foreach($category as $value)
									@if($last != $no)
										{{ $value->name.',' }}
									@else
										{{ $value->name }}
									@endif
									@php $no++ @endphp 
								@endforeach
							</td>
						</tr>
						<tr>
							<th>Tag</th>
							<td>
								@php
									$last = count($tag);
									$no = 1;
								@endphp
								@foreach($tag as $value)
									@if($last != $no)
										{{ $value->name.',' }}
									@else
										{{ $value->name }}
									@endif
									@php $no++ @endphp 
								@endforeach
							</td>
						</tr>
						<tr>
							<th>View Count</th>
							<td>{{ $model->view_count }}</td>
						</tr>
						<tr>
							<th>Created At</th>
							<td>{{ $uti->localDate($model->created_at) }}</td>
						</tr>
						<tr>
							<th>Created By</th>
							<td>{{ $uti->getUser($model->created_by) }}</td>
						</tr>
						<tr>
							<th>Updated At</th>
							<td>{{ $uti->localDate($model->updated_at) }}</td>
						</tr>
						<tr>
							<th>Updated By</th>
							<td>{{ $uti->getUser($model->updated_by) }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>