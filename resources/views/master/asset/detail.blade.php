@extends('base.main')
@section('title') Asset @endsection
@section('page_icon') <i class="fa fa-cube"></i> @endsection
@section('page_title') Detail Asset @endsection
@section('page_subtitle') detail @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
        	@if ($model->active == 0)
	        	<a href="{{ route('asset.publish') }}" class="btn btn-success" title="Publish Asset" id="publish-asset" data-id="{{base64_encode($model->id)}}">
	                <i class="fa fa-edit"></i> Publish
	            </a>
        	@elseif ($model->active == 1)
	        	<div class="btn-group">
	        		<button type="button" class="btn btn-danger">Tools</button>
	        		<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
	        			<span class="caret"></span>
	        			<span class="sr-only">Toggle Dropdown</span>
	        		</button>
	        		<ul class="dropdown-menu" role="menu">
	        			<li><a href="{{ route('asset.takeout') }}" title="Take Out Asset" id="takeout-asset" data-id="{{base64_encode($model->id)}}">Take Out</a></li>
	        			<li><a href="{{ route('asset.cancel-investment') }}" title="Close Investment" id="close-investment-asset" data-id="{{base64_encode($model->id)}}">Close Investment</a></li>
	        		</ul>
	        	</div>
	        	{{-- <a href="{{ route('asset.takeout') }}" class="btn btn-danger" title="Take Out Asset" id="takeout-asset" data-id="{{base64_encode($model->id)}}">
	                <i class="fa fa-edit"></i> Take Out
	            </a> --}}
        	@endif
        	<a href="{{ route('asset.edit', base64_encode($model->id)) }}" class="btn btn-success" title="Edit Asset">
                <i class="fa fa-edit"></i> Update
            </a>
            <a href="{{ route('asset.delete', base64_encode($model->id)) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
        	<a href="{{ route('asset.create') }}" class="btn btn-success" title="Create Asset">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('asset.index') }}" class="btn btn-success" title="Manage Asset">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
@if( !$model->isLanguageComplete() )
<div class="alert alert-warning alert-dismissible">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	<h4><i class="icon fa fa-warning"></i> Please add description for other language</h4>
	Asset won't be active if you not complete the description
</div>
@endif
<div class="nav-tabs-custom">
	<ul class="nav nav-tabs tabs-up" id="friends">
		<li>
			<a href="{{ route('asset.asset-pane', base64_encode($model->id) ) }}" data-target="#tab_asset" class="media_node active span" id="asset_tab" data-toggle="tabajax" rel="tooltip"><i class="fa fa-cubes"></i> Asset </a>
		</li>
		<li>
			<a href="{{ route('asset.rating-pane', base64_encode($model->id)) }}" data-target="#tab_rating" class="media_node span" id="rating_tab" data-toggle="tabajax" rel="tooltip"><i class="fa fa-star"></i> Rating</a>
		</li>
		<li>
			<a href="{{ route('asset.comment-pane', base64_encode($model->id)) }}" data-target="#tab_comment" class="media_node span" id="awaiting_request_tab" data-toggle="tabajax" rel="tooltip"><i class="fa fa-comment"></i> Comment</a>
		</li>
		<li>
			<a href="{{ route('asset.favorite-pane', base64_encode($model->id)) }}" data-target="#tab_favorit" class="media_node span" id="awaiting_request_tab" data-toggle="tabajax" rel="tooltip"><i class="fa fa-flag"></i> Favorite</a>
		</li>
		<li>
			<a href="{{ route('asset.investor-pane', base64_encode($model->id)) }}" data-target="#tab_investor" class="media_node span" id="awaiting_request_tab" data-toggle="tabajax_investor" rel="tooltip"><i class="fa fa-user"></i> Investor</a>
		</li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="tab_asset"></div>
		<div class="tab-pane" id="tab_rating"></div>
		<div class="tab-pane" id="tab_comment"></div>
		<div class="tab-pane" id="tab_favorit"></div>
		<div class="tab-pane" id="tab_investor"></div>
	</div>
</div>


<div class="modal fade" id="takeout-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: 700; font-size: 16px">Take Out Reason</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; top: 15px; right: 15px">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <textarea class="form-control" id="alasanTO" rows="5"></textarea>
        <div class="invalid-feedback alert-failed" id="validasiGagal"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="takeout-submit">Take Out</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="close-investment-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="font-weight: 700; font-size: 16px">Close Investment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; top: 15px; right: 15px">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <textarea class="form-control" id="alasan" rows="5"></textarea>
        <div class="invalid-feedback alert-failed"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="close-investment-submit">Submit</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>

	$('.active[data-toggle="tabajax"]').each(function(e) {
	    var $this = $(this),
	        loadurl = $this.attr('href'),
	        targ = $this.attr('data-target');

	    $.get(loadurl, function(data) {
	        $(targ).html(data);
	    });

	    $this.tab('show');
	    return false;
	});

	$('[data-toggle="tabajax"]').click(function(e) {
	    var $this = $(this),
	        loadurl = $this.attr('href'),
	        targ = $this.attr('data-target');

	    // if(!targ.hasChildNodes())
	    // {
		    $.get(loadurl, function(data) {
		        $(targ).html(data);
		    });
	    // }

	    $this.tab('show');
	    return false;
	});

	$('[data-toggle="tabajax_investor"]').click(function(e) {
	    var $this = $(this),
	        loadurl = $this.attr('href'),
	        targ = $this.attr('data-target');

	    $.get(loadurl, function(data) {
	        $(targ).html(data);

	        $('#datatable-investor').DataTable({
			    responsive : true,
			    processing : true,
			    serverSide : true,
			    pageLength : 20,
			    ajax: "{{ route('table.asset.investor', $model->id) }}",
			    columns: [
			        {data : 'investor', name : 'investor'},
			        {data : 'amount', name : 'amount'},
			        {data : 'tenor', name : 'tenor'},
			        {data : 'interest', name : 'interest'},
			        {data : 'daily_interest', name : 'daily_interest'},
			        {data : 'total_interest', name : 'total_interest'},
			        {data : 'status', name : 'status'}
			    ]
			});
	    });

	    $this.tab('show');
	    return false;
	});

	$('#publish-asset').click(function(e) {
		e.preventDefault();

		var me = $(this),
	        url = me.attr('href'),
	        id = me.attr('data-id'),
	        csrf_token = $('meta[name="csrf-token"]').attr('content');

		swal({
	        title : 'Are you sure sure ?',
	        type : 'warning',
	        showCancelButton : true,
	        confirmButtonColor : '#3085d6',
	        cancelButtonColor : '#d33',
	        confirmButtonText : 'Ya!'
	    }).then((result)=>{
	        if(result.value){
	            $.ajax({
	                url : url,
	                type : 'post',
	                data : {
	                    'id': id,
	                    '_token' : csrf_token
	                },
	                success : function(r){
	                    swal({
	                        type : 'success',
	                        title : 'Success',
	                        text : 'Asset berhasil dipublish.'
	                    }).then((result)=>{
					        location.reload();
					    });
	                },
	                error : function(er){
	                    if(er.status == 401)
	                    {
	                        swal({
	                            type : 'error',
	                            title : 'Error 401',
	                            text : 'Unauthorized action'
	                        });
	                    }
	                    else
	                    {
	                        swal({
	                            type : 'error',
	                            title : 'Failed',
	                            text : 'Data gagal dipublish'
	                        });
	                    }
	                }
	            });
	        }
	    });

	});

	$('#takeout-asset').click(function(e) {
		e.preventDefault();

		$('#takeout-modal').modal();
	});

	$('#takeout-submit').click(function() {
		if ($('#alasanTO').val().trim() == '') {
			$('#alasanTO').addClass('is-invalid');
			$('.alert-failed').text('Reason must be filled.');
			$('.alert-failed').show();
		} else {
			var me = $('#takeout-asset'),
	        url = me.attr('href'),
	        id = me.attr('data-id'),
	        csrf_token = $('meta[name="csrf-token"]').attr('content');

	        $.ajax({
	                url : url,
	                type : 'post',
	                data : {
	                    'id': id,
	                    'alasanTO': $('#alasanTO').val().trim(),
	                    '_token' : csrf_token
	                },
	                success : function(r){
	                	$('#alasanTO').removeClass('is-invalid');
	                	$('.alert-failed').hide();
	                	$('#takeout-modal').modal('hide');

	                    swal({
	                        type : 'success',
	                        title : 'Success',
	                        text : 'Asset berhasil ditakeout.'
	                    }).then((result)=>{
					        location.reload();
					    });
	                },
	                error : function(er){
	                    if(er.status == 401)
	                    {
	                        swal({
	                            type : 'error',
	                            title : 'Error 401',
	                            text : 'Unauthorized action'
	                        });
	                    }
	                    else
	                    {
	                        swal({
	                            type : 'error',
	                            title : 'Failed',
	                            text : 'Data gagal ditakeout'
	                        });
	                    }
	                }
	            });

		}
	});

	$('#close-investment-asset').click(function(e) {
		e.preventDefault();

		$('#alasan').removeClass('is-invalid');
	    $('.alert-failed').hide();
		$('#close-investment-modal').modal();
	});

	$('#close-investment-submit').click(function() {
		if ($('#alasan').val().trim() == '') {
			$('#alasan').addClass('is-invalid');
			$('.alert-failed').text('Reason must be filled.');
			$('.alert-failed').show();
		} else {
			var me = $('#close-investment-asset'),
	        url = me.attr('href'),
	        id = me.attr('data-id'),
	        csrf_token = $('meta[name="csrf-token"]').attr('content');

	        $.ajax({
	            url : url,
	            type : 'post',
	            data : {
	                'id': id,
	                'alasan': $('#alasan').val().trim(),
	                '_token' : csrf_token
	            },
	            success : function(r){
	            	$('#alasan').removeClass('is-invalid');
	            	$('.alert-failed').hide();
	            	$('#close-investment-modal').modal('hide');

	                swal({
	                    type : 'success',
	                    title : 'Success',
	                    text : 'Investasi ditutup'
	                }).then((result)=>{
				        location.reload();
				    });
	            },
	            error : function(er){
	                if(er.status == 401)
	                {
	                    swal({
	                        type : 'error',
	                        title : 'Error 401',
	                        text : 'Unauthorized action'
	                    });
	                }
	                else
	                {
	                    swal({
	                        type : 'error',
	                        title : 'Failed',
	                        text : 'Investasi gagal ditutup'
	                    });
	                }
	            }
	        });
		}
	});
</script>
@endpush

@push('css')
<style>
.featured {
	background: #008d4c;
}	

.form-control.is-invalid {
	border: 1px solid red;
}

.invalid-feedback {
	color: red;
}
.valid-feedback {
	color: green;
}
</style>
@endpush