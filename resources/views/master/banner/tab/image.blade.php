<div class="box-body">
	@if($model->image == null)
		<img src="{{ asset('images/no-img.png') }}" class="img-responsive img-thumbnail">
	@else
		<img src="{{ asset('images/banner/'.$model->image) }}" class="img-responsive">
	@endif
</div>