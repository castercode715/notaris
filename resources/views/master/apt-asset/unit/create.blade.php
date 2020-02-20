@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp

{!! Form::model($model, [
    'route' => $model->exists ? ['unit-asset.update', $model->code_unit ] : 'unit-asset.store',
    'method'=> $method,
]) !!}

    <div class="row">
        
        <div class="col-md-12">
            <div class="form-group">
                <label for="name" class="control-label">Unit Name</label>
                {!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}

                @if($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        {{ $errors->first('name') }}
                    </span>
                @endif
            </div>
        </div>

        
    </div>
    <input type="hidden" name="code_floor" value="{{ $code }}">

    <script type="text/javascript">
        Dropzone.options.apake2 = false;
        var apake2 = new Dropzone('#apake2', {
          url: '{{ route('apt.asset.denah') }}',
          params: { _token: $('meta[name="csrf-token"]').attr('content') },
          paramName: 'img',
          acceptedFiles: 'image/*',
          maxFiles: 1,
          maxFilesize: 128,
          addRemoveLinks: true,
          parallelUploads: 1
        });

        apake2.on('success', function(file, data) {
          $('#denah').val(data);
        });

        apake2.on('removedfile', function(file) {
          $('#denah').val('');
        });
    </script>

{!! Form::close() !!}


