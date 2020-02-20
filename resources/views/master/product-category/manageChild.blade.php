<ul>
	@foreach($childs as $child)
		<li>
			@if(count($model->childs($child->id)))
				<i class="fa fa-plus-circle"></i>
			@endif
		    {{ $child->name }} &nbsp;&nbsp;&nbsp;<i id="btn-editCat" data-id="{{ $child->id }}" data-hreff="{{ route('product-category.edit', $child->id) }}" class="fa fa-edit"></i> | <i id="btn-deleteCat" data-hreff="{{ route('product-category.destroy', $child->id) }}" data-title="{{ $child->name }}" data-id="{{ $child->id }}" class="fa fa-trash"></i>
			@if(count($model->childs($child->id)))

	            @include('master.product-category.manageChild',['childs' => $model->childs($child->id)])
	        @endif
		</li>
	@endforeach
</ul>