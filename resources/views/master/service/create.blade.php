<style type="text/css">
    .select2-selection{
        width: 540px !important;
    }
</style>
@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['services.update', $model->id] : 'services.store',
    'method'=> $method,
]) !!}

	<div class="row">
		
		<div class="col-sm-12">
		    <div class="form-group">
		        <label for="name" class="control-label">Service Name*</label>
		        {!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}
		    </div>
		</div>

		<div class="col-sm-12">
            <div class="form-group">
                <label for="name" class="control-label">Category*</label>
                {!! Form::select('category_id[]', $categories, $category, ['class'=>'form-control select2', 'required','multiple'=>'multiple']) !!}
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label for="name" class="control-label">Related Document*</label>
                {!! Form::select('document[]', $documents, $document, ['class'=>'form-control select2','multiple'=>'multiple','required']) !!}
            </div>
        </div>
		
	</div>

{!! Form::close() !!}


<script>
    $(function () {
      $('.select2').select2()
    });
</script> 