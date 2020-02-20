@extends('base.main')
@section('title')KPR Asset Update @endsection
@section('page_icon') <i class="fa fa-cubes"></i> @endsection
@section('page_title')KPR Asset  @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('apt-asset.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection
@section('content')
    <div class="box box-solid">
        {!! Form::model($model, [
            'route' => ['apt-asset.update', base64_encode($model->code_apt)],
            'method'=> 'put',
            'id' => 'formData',
            'enctype'   => 'multipart/form-data'
        ]) !!}

        <div class="box-body">
            <div class="box-body">
                @if(count($errors) > 0)
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{  $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="asset_name" class="control-label">Code APT*</label>
                            <input type="text" class="form-control" name="code_apt" value="{{ $model->code_apt }}">
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="title" class="control-label">Asset Name*</label>
                            <input type="text" name="name" value="{{ $model->name }}" class="form-control">
                            
                            @if($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('name') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description" class="control-label">Description*</label>
                            <textarea name="description" class="form-control" id="editor1">{{ $model->description }}</textarea>

                            @if($errors->has('description'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('description') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="price" class="control-label">Price*</label>
                            <input type="text" name="price" id="price" value="{{ $model->price }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tenor" class="control-label">Tenor*</label>
                            <input type="text" name="tenor" id="tenor" value="{{ $model->tenor }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="installment" class="control-label">Installment*</label>
                            <input type="text" name="installment" id="installment" value="{{ $model->installment }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="maintenance" class="control-label">Maintenance*</label>
                            <input type="text" name="maintenance" id="maintenance" value="{{ $model->maintenance }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="type_apt" class="control-label">Type APT*</label>
                             {!! Form::select('type_apt', [''=>'- Select -']+['STUDIO 21'=>'STUDIO 21','STUDIO 24'=>'STUDIO 24'], null, ['class'=>'form-control', 'id'=>'type_apt', 'required'=>'required']) !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status" class="control-label">Status*</label>
                             {!! Form::select('status', [''=>'- Select -']+['ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE'], null, ['class'=>'form-control', 'id'=>'status', 'required'=>'required']) !!}
                        </div>
                    </div>
                   
                </div>

                <div class="row">
                
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="country" class="control-label">Country</label>
                            <select name="country" id="country" class="form-control select2 dynamic" data-table="mst_provinces" data-key="countries_id">
                                <option value="">- Select -</option>
                                @foreach($countriesList as $country)
                                    @if(!empty($address))
                                        <option value="{{ $country->id }}" {{ $address->country == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                    @else
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endif
                                @endforeach
                            </select>

                            @if($errors->has('country'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('country') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="province" class="control-label">Province</label>
                            <select name="province" id="mst_provinces" class="form-control dynamic" data-table="mst_regencies" data-key="provinces_id">
                                <option value="">- Select -</option>
                                @if(!empty($provincesList))
                                @foreach($provincesList as $province)
                                    <option value="{{ $province->id }}" {{ $address->province == $province->id ? 'selected':'' }}>{{ $province->name }}</option>
                                @endforeach
                                @endif
                            </select>

                            @if($errors->has('province'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('province') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="regencies_id" class="control-label">Regency</label>
                            <select name="regencies_id" id="mst_regencies" class="form-control select2">
                                <option value="">- Select -</option>
                                @if(!empty($regenciesList))
                                    @foreach($regenciesList as $regency)
                                        <option value="{{ $regency->regencies_id }}" {{ $address->regency == $regency->regencies_id ? 'selected':'' }}>{{ $regency->name }}</option>
                                    @endforeach
                                @endif
                            </select>

                            @if($errors->has('regencies_id'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('regencies_id') }}
                                </span>
                            @endif
                        </div>
                    </div>
                   
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="term_cond" class="control-label">Term Conds*</label>
                            <textarea name="term_cond" class="form-control" id="editor2">{{ $model->term_cond }}</textarea>

                            @if($errors->has('term_cond'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('term_cond') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="asset_name" class="control-label">Berkas/file</label>

                            @if($model->file != null)
                            <ul class="mailbox-attachments clearfix">

                                <li class="attachment" style="width: 420px;" id="attachmentFile">
                                    <span class="mailbox-attachment-icon has-img" style="height: 225px;">
                                        <i class="fa fa-file-pdf-o" style="margin-top: 78px;">
                                            
                                        </i>
                                    </span>

                                    <div class="mailbox-attachment-info">
<<<<<<< HEAD
                                    <a href="{{ asset($model->file) }}" target="_blank" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> File Attachment</a>
=======
                                    <a href="{{ $model->file }}" target="_blank" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> File Attachment</a>
>>>>>>> rian
                                        <span class="mailbox-attachment-size">
                                            2.67 MB
                                            <button class="btn btn-default btn-xs pull-right file-remove" data-asset="{{ $model->code_apt }}" data-attr="file-attachment"><i class="fa fa-trash"></i></a>
                                        </span>
                                    </div>
                                </li>
                                
                            </ul>
                            @endif

                            
                            <div class="dropzone" id="apake3"></div>
                            <input type="hidden" name="file" id="file"></input>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="asset_name" class="control-label">Featured Image</label>

                            @if(!empty($featured))
                            <ul class="mailbox-attachments clearfix">
                                @foreach($featured as $key)
                                    <li class="featured" style="width: 420px;" id="class-featured">
                                        <span class="mailbox-attachment-icon has-img"><img src="{{ asset($key->image) }}" style="width: 100%; height: 225px;" alt="Attachment"></span>

                                        <div class="mailbox-attachment-info">
                                        <a href="#" class="mailbox-attachment-name"><i class="fa fa-camera"></i> Featured Image </a>
                                            <span class="mailbox-attachment-size">
                                                2.67 MB
                                                <button class="btn btn-default btn-xs pull-right featured-remove" data-asset="{{ $key->code_apt }}" data-attr="featured" data-id="{{ $key->id }}"><i class="fa fa-trash"></i></a>
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            @endif

                           
                            <div class="dropzone" id="apake2"></div>
                            <input type="hidden" name="featured" id="featured"></input>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="asset_name" class="control-label">Another Image</label>
                            
                                @if(!empty($other))
                                <ul class="mailbox-attachments clearfix">
                                    @php $no = 1; @endphp
                                    @foreach($other as $img)
                                        <li id="another-image">
                                            <span class="mailbox-attachment-icon has-img"><img src="{{ asset($img->image) }}" style="width: 100%; height: 117px;" alt="Attachment"></span>

                                            <div class="mailbox-attachment-info">
                                            <a href="#" class="mailbox-attachment-name"><i class="fa fa-camera"></i> Image {{ $no }}</a>
                                                <span class="mailbox-attachment-size">
                                                    2.67 MB
                                                    <button class="btn btn-default btn-xs pull-right other-remove" data-asset="{{ $img->code_apt }}" data-attr="other" data-id="{{ $img->id }}"><i class="fa fa-trash"></i></a>
                                                </span>
                                            </div>
                                        </li>
                                    @php $no++; @endphp
                                    @endforeach
                                </ul>
                                @endif

                            <div class="dropzone" id="apake"></div>
                            <input type="hidden" name="bebasNo" value="1" id="bebasNo">
                            <div id="terserah"></div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
         <div class="box-footer">
            {!! Form::submit('Update', ['class'=>'btn btn-primary pull-right']) !!}
        </div>
    </div>
@endsection
@push('scripts')
<script type="text/javascript">

    $('.file-remove').click(function(e){
        e.preventDefault();

        var asset = $(this).data('asset'),
            attr = $(this).data('attr'),
            token = $('input[name="_token"]').val();
        var ithis = $(this);

        swal({
            title : 'Are you sure ?',
            type : 'warning',
            showCancelButton : true,
            confirmButtonColor : '#3085d6',
            cancelButtonColor : '#d33',
            confirmButtonText : 'Yes, delete!'
        }).then((result)=>{
            if(result.value){

                $.ajax({
                    url : "{{ route('apt.asset.remove-file') }}",
                    method : 'post',
                    data : {
                        asset : asset,
                        attr : attr,
                        _token : token
                    },
                    success : function(r){
                        status = true;

                        ithis.closest('#attachmentFile').remove();

                        swal({
                            type : 'success',
                            title : 'Success',
                            text : 'Deleted'
                        });
                    },
                    error : function(er){
                        swal({
                            type : 'error',
                            title : 'Failed',
                            text : 'Failed'
                        });
                    }
                });
            }
            
        });
    });

    $('.featured-remove').click(function(e){
        e.preventDefault();
        var asset = $(this).data('asset'),
            attr = $(this).data('attr'),
            id = $(this).data('id'),
            token = $('input[name="_token"]').val();
        var ithis = $(this);
        
        swal({
            title : 'Are you sure ?',
            type : 'warning',
            showCancelButton : true,
            confirmButtonColor : '#3085d6',
            cancelButtonColor : '#d33',
            confirmButtonText : 'Yes, delete!'
        }).then((result)=>{
            if(result.value){

                $.ajax({
                    url : "{{ route('apt.asset.remove-image') }}",
                    method : 'post',
                    data : {
                        asset : asset,
                        attr : attr,
                        id : id,
                        _token : token
                    },
                    success : function(r){
                        status = true;

                        ithis.closest('#class-featured').remove();

                        swal({
                            type : 'success',
                            title : 'Success',
                            text : 'Deleted'
                        });
                    },
                    error : function(er){
                        swal({
                            type : 'error',
                            title : 'Failed',
                            text : 'Failed'
                        });
                    }
                });
            }
            
        });
    });

    $('.other-remove').click(function(e){
        e.preventDefault();
        var asset = $(this).data('asset'),
            attr = $(this).data('attr'),
            id = $(this).data('id'),
            token = $('input[name="_token"]').val();
        var ithis = $(this);
        
        swal({
            title : 'Are you sure ?',
            type : 'warning',
            showCancelButton : true,
            confirmButtonColor : '#3085d6',
            cancelButtonColor : '#d33',
            confirmButtonText : 'Yes, delete!'
        }).then((result)=>{
            if(result.value){

                $.ajax({
                    url : "{{ route('apt.asset.remove-image') }}",
                    method : 'post',
                    data : {
                        asset : asset,
                        attr : attr,
                        id : id,
                        _token : token
                    },
                    success : function(r){
                        status = true;

                        ithis.closest('#another-image').remove();

                        swal({
                            type : 'success',
                            title : 'Success',
                            text : 'Deleted'
                        });
                    },
                    error : function(er){
                        swal({
                            type : 'error',
                            title : 'Failed',
                            text : 'Failed'
                        });
                    }
                });
            }
            
        });
    });
    
    $(document).ready(function(){

        $("#tenor").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });

        // Format mata uang.
        $( '#price' ).mask('000,000,000,000', {reverse: true});
        $( '#installment' ).mask('000,000,000,000', {reverse: true});
        $( '#maintenance' ).mask('000,000,000,000', {reverse: true});
    })

    $('.dynamic').change(function(){
        if($(this).val() != ''){
            var id = $(this).attr('id'),
                value = $(this).val(),
                table = $(this).data('table'),
                key = $(this).data('key'),
                token = $('input[name="_token"]').val();

            $.ajax({
                url : "{{ route('employee.fetch') }}",
                method : 'post',
                data : {
                    id : id,
                    value : value,
                    _token : token,
                    table : table,
                    key : key
                },
                success : function(result){
                    $('#'+table).html(result);
                }
            });
        }
    });

</script>

<script type="text/javascript">
    Dropzone.options.apake3 = false;
    var apake3 = new Dropzone('#apake3', {
        url: '{{ route('apt.asset.file-asset') }}',
        params: { _token: $('meta[name="csrf-token"]').attr('content') },
        paramName: 'img',
        acceptedFiles: 'application/pdf',
        maxFiles: 1,
        maxFilesize: 128,
        addRemoveLinks: true,
        parallelUploads: 1
    });

    apake3.on('success', function(file, data) {
      $('#file').val(data);
    });

    apake3.on('removedfile', function(file) {
      $('#file').val('');
    });
</script>

<script type="text/javascript">
  Dropzone.autoDiscover = false;
  $(".dropzone").dropzone({
    
  });
</script>

<script type="text/javascript">
    Dropzone.options.apake2 = false;
    // var imageUrl = "http://my.image/file.jpg";
    // var formData = new FormData($('#formData')[0]);
    var apake2 = new Dropzone('#apake2', {
        url: '{{ route('apt.asset.featured') }}',
        params: { _token: $('meta[name="csrf-token"]').attr('content') },
        paramName: 'img',
        acceptedFiles: 'image/*',
        maxFiles: 1,
        maxFilesize: 128,
        addRemoveLinks: true,
        parallelUploads: 1,
        // init: function () {
        //     var name     = $('#imagess').val().trim();
            

        //     $.ajax({
        //       url: '{{ route('apt.asset.featured') }}',
        //       type: 'post',
        //       data: formData,
        //       dataType: 'json',
        //       success: function(data){

        //         var mockFile = {
        //                 name: name,
        //                 size: '1000', 
        //                 type: 'image/jpeg',
        //                 accepted: true            // required if using 'MaxFiles' option
        //             };

        //             this.files.push(mockFile);    // add to files array
        //             this.emit("addedfile", mockFile);
        //             this.emit("thumbnail", mockFile, 'http://localhost:8000/'+ name);
        //             this.emit("complete", mockFile);
                
        //         // $.each(response, function(key,value) {
        //         //     var mockFile = {
        //         //         name: name,
        //         //         size: '1000', 
        //         //         type: 'image/jpeg',
        //         //         accepted: true            // required if using 'MaxFiles' option
        //         //     };

        //         //     this.files.push(mockFile);    // add to files array
        //         //     this.emit("addedfile", mockFile);
        //         //     this.emit("thumbnail", mockFile, 'http://localhost:8000/'+ name);
        //         //     this.emit("complete", mockFile);

        //         //     var existingFileCount = 1;
        //         //     this.options.maxFiles = this.options.maxFiles - existingFileCount;
        //         // });

        //       }
        //     });

            
                
        //     }

    });

    apake2.on('success', function(file, data) {
      $('#featured').val(data);
    });

    apake2.on('removedfile', function(file) {
      $('#featured').val('');
    });
    
</script>

<script type="text/javascript">
    Dropzone.options.apake = false;

    var apake = new Dropzone('#apake', {
        url: '{{ route('apt.asset.images') }}',
        params: { _token: $('meta[name="csrf-token"]').attr('content') },
        paramName: 'img',
        acceptedFiles: 'image/*',
        maxFiles: 7,
        maxFilesize: 128,
        addRemoveLinks: true,
        parallelUploads: 2
    });

    apake.on('success', function(file, data) {
        file.bebas = $('#bebasNo').val();
        $('#terserah').append('<input type="hidden" id="img_' + $('#bebasNo').val() + '" name="image[]" value="' + data + '">');
        $('#bebasNo').val(parseInt($('#bebasNo').val()) + 1);
    });

    apake.on('removedfile', function(file) {
        $('#img_' + file.bebas).remove();
    });
</script>
@endpush
