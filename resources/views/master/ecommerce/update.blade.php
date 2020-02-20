@extends('base.main')
@section('title') Product Ecommerce @endsection
@section('page_icon') <i class="fa fa-opencart"></i> @endsection
@section('page_title') Product Ecommerce @endsection
@section('page_subtitle') Update @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('product.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="box box-solid">
    {!! Form::model($model, [
        'route' => ['product.update', base64_encode($model->id)],
        'method'=> 'put',
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
                
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name" class="control-label">Product Name*</label>
                        <input type="text" name="name" value="{{ $model->name }}" class="form-control">
                        <input type="hidden" name="product_id" id="product_id" value="{{ $model->id }}" class="form-control">
                        
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
                        <label for="desc" class="control-label">Description*</label>
                        <textarea name="desc" class="form-control" id="editor1">{{ $model->desc }}</textarea>

                        @if($errors->has('desc'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('desc') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="term_conds" class="control-label">Term Conds*</label>
                        <textarea name="term_conds" class="form-control" id="editor2">{{ $model->term_conds }}</textarea>

                        @if($errors->has('term_conds'))
                            <span class="invalid-feedback" role="alert">
                                {{ $errors->first('term_conds') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="price" class="control-label">Price*</label>
                        <input type="text" placeholder="IDR" name="price" id="price" value="{{ $model->price }}" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="discount" class="control-label">Discount*</label>
                        <input type="text" placeholder="%" name="discount" id="discount" value="{{ $model->discount }}" class="form-control">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tenor" class="control-label">Tenor*</label>
                        {!! Form::select('tenor[]', $tenorList, $tenor, ['class'=>'form-control select2','multiple'=>'multiple']) !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status" class="control-label">Category*</label>
                        <div id="treeview-checkbox-demo">
                            <ul>
                                @foreach($categorys as $key)
                                    <li data-value="{{ $key->id }}">{{ $key->name }}

                                    @if(count($model->getchildCategory($key->id)))
                                        @include('master.ecommerce.manageChild',['childs' => $model->getchildCategory($key->id)])
                                    @endif
                                @endforeach
                            
                            </ul>
                        </div>
                       
                        <input type="hidden" name="values" id="values">
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <fieldset>
                            <legend>Attribute Product:</legend>
                            <div class="attributes_wrapper">
                                <input type="hidden" id="countattr" name="countattr" value="{{ $countAttr }}">

                            @php $count = 0; @endphp
                            @foreach($attributes as $attribute)
                                <div class="attr-form-group row">

                                <div class="col-md-3">
                                    @if($count < 1)
                                        <label for="images" class="control-label">Name</label>
                                    @else
                                        <label for="images" class="control-label"> </label>
                                    @endif
                                    
                                    <select name="attr_name[]" id="attr_name" class="form-control attr_name" required="required">
                                        <option value="">- Select -</option>
                                        @foreach($attributeList as $value)
                                            @if($value->id == $attribute->attribute_ecommerce_id)
                                                <option value="{!! $value->id !!}" selected>{!! $value->name !!}</option>
                                            @else
                                                <option value="{!! $value->id !!}">{!! $value->name !!}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-7">
                                    @if($count < 1)
                                        <label for="images" class="control-label">Value</label>
                                    @else
                                        <label for="images" class="control-label"> </label>
                                    @endif
                                    
                                    <input type="text" name="attr_value[]" value="{{ $attribute->value }}" class="form-control">
                                </div>

                                <div class="col-md-2">
                                    @if($count < 1)
                                        <a href="javascript:void(0);" class="add_attr_button btn btn-sm btn-success" title="Add Field"  style="margin-top: 25px;"><i class="fa fa-plus"></i></a>
                                    @else
                                        <a href="javascript:void(0);" class="remove_attr_button btn btn-sm btn-danger" style="margin-top: 22px;" title="Remove"><i class="fa fa-close"></i></a>
                                    @endif
                                   
                                </div>
                                </div>

                            @php $count++; @endphp
                            @endforeach
                            </div>


                        </fieldset>
                    </div>

                    @if($errors->has('attr_name.*'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('attr_name.*') }}
                        </span>
                    @endif
                    @if($errors->has('attr_value.*'))
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('attr_value.*') }}
                        </span>
                    @endif
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status" class="control-label">Status*</label>
                         {!! Form::select('status', [''=>'- Select -']+['ACTIVE'=>'ACTIVE','INACTIVE'=>'INACTIVE'], null, ['class'=>'form-control', 'id'=>'status', 'required'=>'required']) !!}
                    </div>
                </div>

            </div>

            
            <div class="row">
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="asset_name" class="control-label">Featured Image</label>
                        
                        <div class="dropzone" id="apake2"></div>
                        <input type="hidden" name="featured" id="featured"></input>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="asset_name" class="control-label">Another Image </label>
                                
                        <div class="dropzone" id="apake"></div>
                        <input type="hidden" name="bebasNo" value="1" id="bebasNo">
                        <div id="terserah"></div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <div class="box-footer">
        {!! Form::submit('Update Product', ['class'=>'btn btn-primary pull-right']) !!}
    </div>
    {!! Form::close() !!}
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    /* ---------------------------------------
        AJAX TREEVIEW CATEGORY
    -----------------------------------------*/
    $(document).ready(function(){
        $.ajax({ 
            url: "{{ route('product.ecommerce.getcategory', $model->id) }}",
            context: document.body,
                success: function(data){
                    $('#values').val(data);

                    $('#treeview-checkbox-demo').treeview({
                        debug : true,
                        data : data
                    });

                    $('#treeview-checkbox-demo').on('click', function(){ 
                        $('#values').val(
                            $('#treeview-checkbox-demo').treeview('selectedValues')
                        );
                    });
                }
        });
    });

</script>
<script>
    $(document).ready(function(){

        $("#tenor").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });

        // $("#bunga").keypress(function (e) {
        //     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //         return false;
        //     }
        // });

        $("#discount").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });

        $( '#price' ).mask('000,000,000,000', {reverse: true});
    })

    /* Script datatable */

    window.table = $('#datatable').DataTable({
        responsive : true,
        processing : true,
        serverSide : true,
        ajax: "{{ route('table.ecommerce.product') }}",
        columns: [
            {data : 'DT_Row_Index', name : 'id'},
            {data : 'name', name : 'name'},
            {data : 'status', name : 'status'},
            {data : 'created_at', name : 'created_at'},
            {data : 'created_by', name : 'created_by'},
            {data : 'action', name : 'action'}
        ]
    });

    /* End Script datatable */
</script>

<script type="text/javascript">
    /* Script Dropzone */

    Dropzone.options.apake2 = false;
    var apake2 = new Dropzone('#apake2', {
      url: '{{ route('product.ecommerce.featured') }}',
      params: { _token: $('meta[name="csrf-token"]').attr('content') },
      paramName: 'img',
      acceptedFiles: 'image/*',
      maxFiles: 1,
      maxFilesize: 128,
      addRemoveLinks: true,
      parallelUploads: 1
    });

    var id = document.getElementById("product_id").value;
    var _token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
          url: '{{ route('product.ecommerce.show_image') }}',
          data: { id, _token },
          method: 'post',
          success: function(data) {
            var i = 1;

            $.each(data.imgs, function(key, value) {
                // alert(mockFile, value.url);
              var mockFile = { name: value.nama, size: value.size, imgNo: i, accepted: true };
              apake2.emit('addedfile', mockFile);
              apake2.createThumbnailFromUrl(mockFile, '../../../'+value.url);
              apake2.emit('complete', mockFile);
              apake2.files.push(mockFile);
              // $('#imgList').append('<input type="hidden" id="img_' + i + '" name="img[]" value="' + value.url + '">');
              i = i + 1;

            });

            // $('#imgNo').val(i);
        }
    });

    apake2.on('success', function(file, data) {
      $('#featured').val(data);
    });

    apake2.on('removedfile', function(file) {
      $('#featured').val('');
    });

    //////////////////////////////////////////

    Dropzone.options.apake = false;
    var apake = new Dropzone('#apake', {
        url: '{{ route('product.ecommerce.images') }}',
        params: { _token: $('meta[name="csrf-token"]').attr('content') },
        paramName: 'img',
        acceptedFiles: 'image/*',
        maxFiles: 7,
        maxFilesize: 128,
        addRemoveLinks: true,
        parallelUploads: 2
    });

    var id_imgs = document.getElementById("product_id").value;
    var _token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
          url: '{{ route('product.ecommerce.show_images') }}',
          data: { id_imgs, _token },
          method: 'post',
          success: function(data) {
            var i = 1;

            $.each(data.imgs, function(key, value) {
                // alert(mockFile, value.url);
              var mockFile = { name: value.nama, size: value.size, bebas: i, accepted: true };
              apake.emit('addedfile', mockFile);
              apake.createThumbnailFromUrl(mockFile, '../../../'+value.url);
              apake.emit('complete', mockFile);
              apake.files.push(mockFile);
              $('#terserah').append('<input type="hidden" id="img_' + i + '" name="image[]" value="' + value.url + '">');
              i = i + 1;

            });

            $('#bebasNo').val(i);
        }
    });

    apake.on('success', function(file, data) {
        file.bebas = $('#bebasNo').val();
        $('#terserah').append('<input type="hidden" id="img_' + $('#bebasNo').val() + '" name="image[]" value="' + data + '">');
        $('#bebasNo').val(parseInt($('#bebasNo').val()) + 1);
    });

    apake.on('removedfile', function(file) {

        console.log(file);
        $('#img_' + file.bebas).remove();
    });

    /* End Script Dropzone */
</script>

<script type="text/javascript">

var valtext = document.getElementById("countattr").value;
    maxField = 10 - valtext,
    addButton = $('.add_button'),
    addAttrButton = $('.add_attr_button'),
    attr_wrapper = $('.attributes_wrapper'),
    x = 0,
    y = 0;

$(addAttrButton).click(function(){

    if(y < maxField){
        y++;
        $(attr_wrapper).append('<div class="attr-form-group row"><div class="col-md-3"><label for="images" class="control-label"> </label><select name="attr_name[]" class="form-control select attr_name attr_'+y+'" id="attr_name" required="required"></select></div><div class="col-md-7"><label for="images" class="control-label"> </label><input type="text" name="attr_value[]" class="form-control" required="required"></div><div class="col-md-2"><a href="javascript:void(0);" class="remove_attr_button btn btn-sm btn-danger" style="margin-top: 20px;" title="Remove"><i class="fa fa-close"></i></a></div></div>');

        var token = $('input[name="_token"]').val();

        $.ajax({
            url : "{{ route('product.ecommerce.fetch-attributes') }}",
            method : 'post',
            data : {
                _token : token
            },
            success : function(result){
                $('.attr_'+y).html(result);
            }
        });
    }

});

$(attr_wrapper).on('click', '.remove_attr_button', function(e){

    e.preventDefault();
    $(this).closest('.attr-form-group').remove();
    y--;
});

</script>

<script type="text/javascript">
    $('.featured-remove').click(function(e){
        e.preventDefault();
        var attr = $(this).data('attr'),
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
                    url : "{{ route('product.remove-image') }}",
                    method : 'post',
                    data : {
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
        var attr = $(this).data('attr'),
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
                    url : "{{ route('product.remove-image') }}",
                    method : 'post',
                    data : {
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
</script>
@endpush