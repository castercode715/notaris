@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['member.update', $model->id] : 'member.store',
    'method'=> $method,
]) !!}

    <div class="form-group">
        <label for="code" class="control-label">Language</label>
        {!! Form::select('code', [''=>'- Select -'] + $lang, 'IND', ['class'=>'form-control',' id'=>'code', 'disabled'=>'disabled']) !!}
    </div>

    <div class="form-group">
        <label for="description" class="control-label">Name</label>
        {!! Form::text('description', null, ['class'=>'form-control', 'id'=>'description']) !!}
    </div>

    {{-- <div class="form-group">
        <label for="active" class="control-label">Active</label>
        <div>
            {!! Form::checkbox('active', null, null, ['id'=>'active']) !!} 
        </div>
    </div> --}}

{!! Form::close() !!}