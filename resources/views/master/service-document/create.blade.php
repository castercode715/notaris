<style type="text/css">
    .select2-selection{
        width: 540px !important;
    }
</style>

@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['service-document.update', $model->id] : 'service-document.store',
    'method'=> $method,
]) !!}

    <div class="row">
        
        <div class="col-sm-12">
            <div class="form-group">
                <label for="name" class="control-label">Service Name*</label>
                {!! Form::select('service_id', $services, null, ['class'=>'form-control select2']) !!}
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label for="name" class="control-label">Related Document*</label>
                {!! Form::select('document[]', $documents, $document, ['class'=>'form-control select2','multiple'=>'multiple']) !!}
            </div>
        </div>
        
    </div>

{!! Form::close() !!}


<script>
    $(function () {
      $('.select2').select2()
    });
</script> 
