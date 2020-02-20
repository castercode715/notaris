@extends('base.main')
@section('title') Village @endsection
@section('page_icon') <i class="fa fa-user"></i> @endsection
@section('page_title') Village @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
			{{-- <a href="/master/village/import"> <img src="/images/icon-excel.png" height="35"></a>&nbsp;&nbsp; --}}
            <a href="{{ route('village.create') }}" class="btn btn-success modal-show-village" title="Create">
                <i class="fa fa-plus"></i> Create
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-search"></i> Search Village</h3>
        </div>
        <div class="box-body">
            <div class="box-body">
                {!! Form::open(['route'=>'village.search','method'=>'post','id'=>'search-form']) !!}
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label for="id">ID</label>
                            <input type="text" name="id" class="form-control" />
                        </div>
                        <div class="col-md-4">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            <input type="submit" name="submit" value="Search" style="margin-top: 25px;" class="btn btn-primary btn-search" />
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
            <div class="box-footer" id="search-result">
                
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-village" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modal-title-village">Modal Title</h4>
                </div>
                <div class="modal-body">
                    <div class="modal-body" id="modal-body-village">
                    </div>
                </div>
                <div class="modal-footer" id="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="modal-btn-save-village">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>

$('body').on('change','.dynamic',function(){
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

var search_form = $('#search-form');

$('.btn-search').click(function(e){
    e.preventDefault();

    search_form.submit();
});

search_form.submit(function(e){
    e.preventDefault();

    $.ajax({
        url : '{{ route("village.search") }}',
        method : 'post',
        data : $(this).serialize(),
        success : function(r) {
            $('#search-result').html(r);

            $('#datatable').DataTable({
                responsive : true,
                processing : true
            });
        },
        error : function(e) {
            swal({
                type : 'error',
                title : '404',
                text : ''
            });
        }
    });
});

/* *********************************
* Modal
* *********************************/
$('body').on('click', '.modal-show-village', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'),
        id = me.data('id');
    
    $('#modal-title-village').text(title);
    $('#modal-btn-save-village').removeClass('hide').text(me.hasClass('edit') ? 'Update' : 'Create');
    
    $.ajax({
        url: url,
        dataType: 'html',
        success: function(response)
        {
            $('#modal-village').modal('show');
            $('#modal-body-village').html(response);
        },
        error : function(error)
        {
            $('#modal-village').modal('hide');
            swal({
                type : 'error',
                title : 'Error 401',
                text : 'Unauthorized action'
            });
        }
    });

});

$('#modal-btn-save-village').click(function(e){
    e.preventDefault();
    
    var form = $('#modal-body-village form'),
        url = form.attr('action'),
        method = form.attr('method');

    form.find('.help-block').remove();
    form.find('.form-group').removeClass('has-error');
    
    $.ajax({
        url : url,
        method : method,
        data : form.serialize(),
        success : function(response){
            console.log('response : '+response);
            form.trigger('reset');
            $('#modal-village').modal('hide');
            /*$('#datatable').DataTable().ajax.reload();
            $('#datatable-internet_banking').DataTable().ajax.reload();
            $('#datatable-atm').DataTable().ajax.reload();*/
            // search_form.submit();
            $.ajax({
                url : 'search-result/village/id/'+response,
                method : 'get',
                success : function(r) {
                    $('#search-result').html(r);
                }
            });

            swal({
                type : 'success',
                title : 'Success',
                text : 'Saved'
            });
        },
        error : function(e){
            console.log('error : '+e);
            var response = e.responseJSON;
            // console.log(response);
            if($.isEmptyObject(response) == false)
            {
                $.each(response.errors, function(key, value) {
                    $('#' + key)
                        .closest('.form-group')
                        .addClass('has-error')
                        .append('<span class="help-block">'+ value +'</span>')
                });
            }
        }
    });
});

$('body').on('click', '.btn-delete-village', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'),
        csrf_token = $('meta[name="csrf-token"]').attr('content');
    
    swal({
        title : 'Are you sure '+title+' ?',
        type : 'warning',
        showCancelButton : true,
        confirmButtonColor : '#3085d6',
        cancelButtonColor : '#d33',
        confirmButtonText : 'Ya, hapus!'
    }).then((result)=>{
        if(result.value){
            $.ajax({
                url : url,
                type : 'post',
                data : {
                    '_method': 'DELETE',
                    '_token' : csrf_token
                },
                success : function(r){
                    // $.ajax({
                    //     url : 'search-result/village/id/'+r,
                    //     method : 'get',
                    //     success : function(r) {
                    //         $('#search-result').html(r);
                    //     }
                    // });
                    $('#search-result').html('');

                    swal({
                        type : 'success',
                        title : 'Success',
                        text : 'Data berhasil dihapus'
                    });
                },
                error : function(er){
                    if(er.status == 401)
                    {
                        swal({
                            type : 'error',
                            title : 'Error 401',
                            text : 'Unauthorized action'
                        });
                    }
                    else
                    {
                        swal({
                            type : 'error',
                            title : 'Failed',
                            text : 'Data gagal dihapus'
                        });
                    }
                }
            });
        }
    });

});

</script>
@endpush