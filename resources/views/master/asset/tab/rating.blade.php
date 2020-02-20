@if(count($rating) != 0)
	@foreach($rating as $r)
	<div class="post">
		<div class="user-block">
			<img class="img-circle img-bordered-sm" src="/images/investor/{{ $r->photo }}" alt="user image">
			<span class="username">
				<a href="#">{{ $r->full_name }}</a>
			</span>
	        <span class="description">Shared publicly - {{ date('d M Y', strtotime($r->created_at)) }}</span>
		</div>
		<table width="100px" style="margin-bottom: 0;">
			<tr>
				@php $no = 1; @endphp
				@while($no <= 5)
					@if($no <= $r->rating)
						<td><i class="fa text-yellow fa-star"></i></td>
					@else
						<td><i class="fa text-yellow fa-star-o"></i></td>
					@endif
					@php $no++; @endphp
				@endwhile
			</tr>
		</table>
		<p>{{ html_entity_decode($r->review) }}</p>
	</div>
	@endforeach
@else
	<div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="fa fa-warning"></i> <strong>Alert!</strong> Rating not found
    </div>
@endif