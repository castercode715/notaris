@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['product-category.update', $model->id] : 'product-category.store',
    'method'=> $method,
]) !!}
	
	 <div class="form-group">
        <label for="name" class="control-label">Category Name</label>
        {!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}
    </div>

    <div class="form-group">
        <label for="name" class="control-label">Parent</label>
        {!! Form::select('parent_id', $allCategory, null, ['class'=>'form-control select2']) !!}
    </div>
    
    <div class="form-group">
        <label for="name" class="control-label">Icon</label>
        @if($model->image != null)
        
          <ul class="mailbox-attachments clearfix">
            <li style="width: 100%;">
              <span class="mailbox-attachment-icon"><i class="fa fa-file-o"></i></span>
                <div class="mailbox-attachment-info">
                  <a href="{{ asset($model->image) }}" target="_blank" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i>Icons </a>
                    <span class="mailbox-attachment-size">
                          1,245 KB
                    </span>
                </div>
            </li>
          </ul>
        
        @endif

        <div class="dropzone" id="apake3"></div>
        <input type="hidden" name="icon_category2" id="icon_category2"></input>

    </div>

    <script type="text/javascript">
        Dropzone.options.apake3 = false;
        var apake3 = new Dropzone('#apake3', {
          url: '{{ route('product.category.icon') }}',
          params: { _token: $('meta[name="csrf-token"]').attr('content') },
          paramName: 'img',
          acceptedFiles: 'image/*',
          maxFiles: 1,
          maxFilesize: 128,
          addRemoveLinks: true,
          parallelUploads: 1
        });

        apake3.on('success', function(file, data) {
          $('#icon_category2').val(data);
        });

        apake3.on('removedfile', function(file) {
          $('#icon_category2').val('');
        });
    </script>
        


{!! Form::close() !!}