@extends('base.main')
@section('title') News @endsection
@section('page_icon') <i class="fa fa-newspaper-o"></i> @endsection
@section('page_title') News @endsection
@section('page_subtitle') Detail @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
        	<a href="{{ route('news.edit', base64_encode($model->id) ) }}" class="btn btn-success" title="Edit news">
                <i class="fa fa-edit"></i> Update
            </a>
            <a href="{{ route('news.delete', base64_encode($model->id)) }}" class="btn btn-danger btn-delete2" title="Delete">
                <i class="fa fa-trash"></i> Delete
            </a>
        	<a href="{{ route('news.create') }}" class="btn btn-success" title="Create news">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('news.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
@if( !$news->isComplete() )
<div class="alert alert-warning alert-dismissible">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	<h4><i class="icon fa fa-warning"></i> Please add description for other language</h4>
	Asset won't be active if you not complete the description
</div>
@endif
<div class="nav-tabs-custom">
	<ul class="nav nav-tabs tabs-up" id="friends">
		<li class="active">
			<a href="#tab_detail" data-toggle="tab"><i class="fa fa-list"></i> Detail </a>
		</li>
		<li>
			<a href="#tab_comment" data-toggle="tab"><i class="fa fa-comment"></i> Comment </a>
		</li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="tab_detail">
			@include('master.news.tab.detail')
		</div>
		<div class="tab-pane" id="tab_comment">
			@include('master.news.tab.comment')
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
// showChildBtn = document.getElementsByClassName('show-child');

// window.hideChildBtn = $('.hide-child');

$('body').on('click', '.show-child', function(e){
	e.preventDefault();

	var me = $(this);
	var url = $(this).attr('href');
	var vid = $(this).data('id');
	var childBox = $(this).closest('.box-comment-child');

	$.ajax({
		url : url,
		method : 'get',
		dataType : 'json',
		success : function(r) {

			$.each(r, function( key, value) {
				console.log(value.isVisible);
				$('#wrapper-'+vid).append(
					'<div class="post" style="padding-bottom: 25px; margin-left:25px; margin-top:10px;">'
						+'<div class="user-block">'
							+'<img class="img-circle img-bordered-sm" src="/images/investor/'+value.image+'" alt="user image" />'
							+'<span class="username">'
								+'<a href="#">'+value.fullname+'</a>'
								+'<div class="pull-right btn-box-tool">'
									+'<a href="/master/news/comment/approve/'+value.id+'" class="btn btn-xs btn-primary btn-comment-approve" id="comment-approve-'+value.id+'" data-id="'+value.id+'" style="display:'+(value.isVisible==1 ? 'none':'')+';"><i class="fa fa-check"></i></a>'
									+'<a href="/master/news/comment/reject/'+value.id+'" class="btn btn-xs btn-danger btn-comment-reject" id="comment-reject-'+value.id+'" data-id="'+value.id+'" style="display:'+(value.isVisible==0 ? 'none':'')+';"><i class="fa fa-times"></i></a>'
								+'</div>'
							+'</span>'
							+'<span class="description">Shared publicly - '+value.createdAt+'</span>'
						+'</div>'
						+'<p>'+value.comment+'</p>'
						+'<div class="box-comment-child">'
							+'<div class="box-tools">'
								+'<a href="/master/comment-child/'+value.id+'" class="link-black text-sm show-child" id="show-comment-'+value.id+'" data-id="'+value.id+'" style="display: '+ (value.childTotal==0?'none':'') +';"><i class="fa fa-comments-o margin-r-5" ></i> Show Comments('+value.childTotal+')</a>'
								+'<a href="javascript:void(0)" class="link-black text-sm hide-child" style="display: none;" id="hide-comment-'+value.id+'" data-id="'+value.id+'"><i class="fa fa-comments-o margin-r-5"></i> Hide Comments</a>'
							+'</div>'
							+'<div id="wrapper-'+value.id+'"></div>'
						+'</div>'
					+'</div>'
				);
			});

			me.hide();
			$('#hide-comment-'+vid).show();
		}
	});
});

$('body').on('click', '.hide-child', function(e){
	e.preventDefault();

	var me = $(this),
		vid = me.data('id');

	$('#wrapper-'+vid).html('');
	me.hide();
	$('#show-comment-'+vid).show();
});

$('body').on('click', '.btn-comment-approve', function(e){
	e.preventDefault();
	var me = $(this),
		url = me.attr('href'),
		id = me.data('id');

	$.ajax({
		url : url,
		method : 'get',
		success : function(r) {
			$('#comment-approve-'+id).hide();
			$('#comment-reject-'+id).show();

			swal({
				type : 'success',
                title : 'Success',
                text : 'Approved'
			});
		},
		error : function(e) {
			swal({
                type : 'error',
                title : 'Error',
                text : 'Failed'
            });
		}
	});
});

$('body').on('click', '.btn-comment-reject', function(e){
	e.preventDefault();
	var me = $(this),
		url = me.attr('href'),
		id = me.data('id');

	$.ajax({
		url : url,
		method : 'get',
		success : function(r) {
			$('#comment-approve-'+id).show();
			$('#comment-reject-'+id).hide();

			swal({
				type : 'success',
                title : 'Success',
                text : 'Approved'
			});
		},
		error : function(e) {
			swal({
                type : 'error',
                title : 'Error',
                text : 'Failed'
            });
		}
	});
});
</script>
@endpush