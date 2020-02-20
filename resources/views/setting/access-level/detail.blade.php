@extends('base.main')
@section('title') Access Role @endsection
@section('page_icon') <i class="fa fa-cog"></i> @endsection
@section('page_title') Detail Access Role @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('access-level.index') }}" class="btn btn-success" title="Manage Access Role">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
	<div class="box-body">
		<div class="box-body">
			<h4>Role : <strong>{{ $role->role }}</strong></h4>
			<table id="datatable" class="table table-hover table-condensed table-bordered">
	            <thead>
	            	<tr>
	            		<th width="50px">No</th>
	            		<th>Module</th>
	            		<th>Read</th>
	            		<th>Create</th>
	            		<th>Update</th>
	            		<th>Delete</th>
	            		<th>Show</th>
	            	</tr>
	            </thead>
	            @php
	            	$no = 1;
	            	$action = ['R','C','U','D','S']; 
	            @endphp
	            <tbody>
	            	@foreach($modules as $module)
		            	<tr>
		            		<td>{{ $no++ }}</td>
		            		<td><strong>{{ $module->module }}</strong></td>
		            		@for($a = 0; $a < count($action); $a++)
		            			@php $checked = false; @endphp
		            			@if($model->isActionChecked($module->id, $action[$a], $actions))
		            				@php $checked = true; @endphp
		            			@endif
		            			<td width="100px" style="text-align: center;">
		            				@if($model->isHasChild($module->id) > 0 && in_array($action[$a], ['R','C','U','D']))
		            					@php continue; @endphp
		            				@endif
		            				<input type="checkbox" class="checkbox_act" data-role="{{ $role->id }}" data-module="{{ $module->id }}" data-act="{{ $action[$a] }}" {{ $checked ? "checked" : "" }}>
		            			</td>	
		            		@endfor
		            	</tr>
	            		@if($module->parent_id == null)
	            			@foreach($model->getModuleChild($module->id) as $child)
	            			<tr>
	            				<td>{{ $no++ }}</td>
			            		<td style="padding-left: 30px;">{{ $child->module }}</td>

			            		@for($b = 0; $b < count($action); $b++)
			            			@php $checked = false; @endphp
			            			@if($model->isActionChecked($child->id, $action[$b], $actions))
			            				@php $checked = true; @endphp
			            			@endif
			            			<td width="100px" style="text-align: center;">
			            				@if($model->isHasChild($child->id) > 0 && in_array($action[$b], ['R','C','U','D']))
			            					@php continue; @endphp
			            				@endif
				            			<input type="checkbox" class="checkbox_act" data-role="{{ $role->id }}" data-module="{{ $child->id }}" data-act="{{ $action[$b] }}" {{ $checked ? "checked" : "" }}>
				            		</td>
			            		@endfor	
	            			</tr>
	            			@php
	            				$child_second = $model->getModuleChild($child->id);
	            			@endphp
	            			@if($child_second != null)
	            				@foreach($child_second as $child2)
		            			<tr>
		            				<td>{{ $no++ }}</td>
				            		<td style="padding-left: 60px;">{{ $child2->module }}</td>

				            		@for($c = 0; $c < count($action); $c++)
				            			@php $checked = false; @endphp
				            			@if($model->isActionChecked($child2->id, $action[$c], $actions))
				            				@php $checked = true; @endphp
				            			@endif
				            			<td width="100px" style="text-align: center;">
					            			<input type="checkbox" class="checkbox_act" data-role="{{ $role->id }}" data-module="{{ $child2->id }}" data-act="{{ $action[$c] }}" {{ $checked ? "checked" : "" }}>
					            		</td>
				            		@endfor
		            			</tr>
	            				@endforeach
	            			@endif
	            			@endforeach
	            		@endif
	            	@endforeach
	            </tbody>
	        </table>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>

	$('.checkbox_act').on('click', function(){
		var role = $(this).data('role'),
			module = $(this).data('module'),
			act = $(this).data('act'),
			token = $('meta[name="csrf-token"]').attr('content');

		var action = '';
		if(act == 'R')
			action = 'Read';
		else if(act == 'C')
			action = 'Create';
		else if(act == 'U')
			action = 'Update';
		else if(act == 'D')
			action = 'Delete';
		else if(act == 'S')
			action = 'Show';
		else
			action = 'Unknown';

		if($(this).is(":checked")) 
		{
			// alert('checked '+role+'-'+module+'-'+act);
			$.ajax({
				url : "{{ route('access-level.add-act') }}",
				method : 'post',
				data : {
					role : role,
					module : module,
					action : act,
					_token : token
				},
				success : function(result){
					swal({
                        type : 'success',
                        title : 'Success',
                        text : 'Module : '+module+', Action : '+action+', Added'
                    });
				},
				error : function(er){
                    swal({
                        type : 'error',
                        title : 'Failed',
                        text : 'Failed'
                    });
                }
			});
		}
		else
		{
			// alert('unchecked '+role+'-'+module+'-'+act);
			$.ajax({
				url : '{{ route("access-level.remove-act") }}',
				method : 'post',
				data : {
					role : role,
					module : module,
					action : act,
					_token : token
				},
				success : function(r){
					swal({
                        type : 'success',
                        title : 'Deleted',
                        text : 'Module : '+module+', Action : '+action+', Deleted'
                    });
				},
				error : function(er){
                    swal({
                        type : 'error',
                        title : 'Failed',
                        text : 'Failed'
                    });
                }
			});
		}
	});

</script>
@endpush