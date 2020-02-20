@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp

{!! Form::model($model, [
    'route' => $model->exists ? ['floor.update', $model->code_floor ] : 'floor.store',
    'method'=> $method,
]) !!}

    <div class="row">
        
        <div class="col-md-12">
            <div class="form-group">
                <label for="name" class="control-label">Floor name</label>
                {!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}

                @if($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        {{ $errors->first('name') }}
                    </span>
                @endif
            </div>
        </div>
        

        <div class="col-md-12">
            <div class="form-group">
                <label for="name" class="control-label">Denah</label>
                @if($model->denah != null)
                
                  <ul class="mailbox-attachments clearfix">
                    <li style="width: 100%;">
                      <span class="mailbox-attachment-icon"><i class="fa fa-file-o"></i></span>
                        <div class="mailbox-attachment-info">
                          <a href="{{ asset($model->denah) }}" target="_blank" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i>Denah lokasi </a>
                            <span class="mailbox-attachment-size">
                                  1,245 KB
                              <!-- <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a> -->
                            </span>
                        </div>
                    </li>
                  </ul>
                
                @endif

                <div class="dropzone" id="apake2"></div>
                    <input type="hidden" name="denah" id="denah"></input>

            </div>
        </div>

        
    </div>
    <input type="hidden" name="code_apt" value="{{ $code }}">

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


