<div class="box-body">
	@if($model->video == null)
		<img src="{{ asset('images/no-img.png') }}" class="img-responsive img-thumbnail">
	@else
		<video width="440" height="300" controls>
			<source src="{{ asset('video/banner/'.$model->video) }}" type="video/mp4">
			<source src="{{ asset('video/banner/'.$model->video) }}" type="video/ogg">
			Your browser does not support the video
		</video>
	@endif
</div>