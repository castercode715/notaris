<ul>
	@foreach($childs as $child)
	<li data-value="{{ $child->id }}">{{ $child->name }}
		@if(count($model->getchildCategory($child->id)))
	        @include('master.ecommerce.manageChild',['childs' => $model->getchildCategory($child->id)])
	    @endif
	</li>
	@endforeach
</ul>