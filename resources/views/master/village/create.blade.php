@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['village.update', $model->id] : 'village.store',
    'method'=> $method,
]) !!}

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="country" class="control-label">Country*</label>
                @if(!$model->exists)
                    {!! Form::select('country', [''=>'- Select -']+$countries, null, ['class'=>'form-control dynamic','data-table'=>'mst_provinces','data-key'=>'countries_id','id'=>'country']) !!}
                @else
                    {!! Form::select('country', [''=>'- Select -']+$countries, null, ['class'=>'form-control dynamic','data-table'=>'mst_provinces','data-key'=>'countries_id','id'=>'country']) !!}
                @endif
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="mst_provinces" class="control-label">Province*</label>
                @if(!$model->exists)
                    {!! Form::select('mst_provinces', [''=>'- Select -'], null, ['class'=>'form-control dynamic', 'id'=>'mst_provinces', 'data-table'=>'mst_regencies', 'data-key'=>'provinces_id']) !!}
                @else
                    {!! Form::select('mst_provinces', [''=>'- Select -']+$provinces, null, ['class'=>'form-control dynamic', 'id'=>'mst_provinces', 'data-table'=>'mst_regencies', 'data-key'=>'provinces_id']) !!}
                @endif
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="mst_regencies" class="control-label">Regency*</label>
                @if(!$model->exists)
                    {!! Form::select('mst_regencies', [''=>'- Select -'], null, ['class'=>'form-control dynamic', 'id'=>'mst_regencies', 'data-table'=>'mst_districts', 'data-key'=>'regencies_id',]) !!}
                @else
                    {!! Form::select('mst_regencies', [''=>'- Select -']+$regencies, null, ['class'=>'form-control', 'id'=>'mst_regencies', 'data-table'=>'mst_districts', 'data-key'=>'regencies_id']) !!}
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="mst_districts" class="control-label">District*</label>
                @if(!$model->exists)
                    {!! Form::select('mst_districts', [''=>'- Select -'], null, ['class'=>'form-control', 'id'=>'mst_districts', 'data-table'=>'mst_districts', 'data-key'=>'regencies_id',]) !!}
                @else
                    {!! Form::select('mst_districts', [''=>'- Select -']+$districts, null, ['class'=>'form-control', 'id'=>'mst_districts', 'data-table'=>'mst_districts', 'data-key'=>'regencies_id']) !!}
                @endif
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="id" class="control-label">ID*</label>
                {!! Form::text('id', null, ['class'=>'form-control', 'id'=>'id']) !!}
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="name" class="control-label">Name*</label>
                {!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}
            </div>
        </div>
    </div>

{!! Form::close() !!}