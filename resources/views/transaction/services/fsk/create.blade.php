<style type="text/css">
    .select2-container {
        width: 100% !important;
    }
</style>
@extends('base.main')
@section('title') Formulir Service Klien @endsection
@section('page_icon') <i class="fa fa-edit"></i> @endsection
@section('page_title') Formulir Service Klien @endsection
@section('page_subtitle') Create @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('fsk.index') }}" class="btn btn-primary" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>

            
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-default" data-select2-id="14">
            <div class="box-header with-border">
                <h3 class="box-title">Data Customer</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>

            <div class="box-body" data-select2-id="13">
                <div class="row">
                    <div class="col-md-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">No FSK*</label>
                        <input type="text" class="form-control" id="fsk_no" readonly="true" name="fsk_no" value="{{ $fsk_no }}">
                    </div>

                    <div class="form-group">
                        <label>Tanggal*</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right" id="datepicker">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Penerima*</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asset_name" class="control-label">Nama Pemohon*</label>
                                <div class="input-group">
                                    <select name="nama_pemohon" id="nama_pemohon" class="form-control dynamic select2" >
                                        <option value="0">- Silahkan Pilih -</option>
                                        @foreach($client as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-addon">
                                        <a href="{{ route('fsk-client.create') }}" data-flag="pemohon" title="Tambah Client" class="modal-show-fsk"><i class="fa fa-plus"></i></a>
                                    </span>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="bank" style="display: none;">
                                <div class="form-group">
                                    <label for="asset_name" class="control-label">Debitur*</label>
                                    <div class="input-group">
                                        <select name="debitur" id="debitur" class="form-control dynamic select2" >
                                            <option value="0">- Silahkan Pilih -</option>
                                            @foreach($debitur as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-addon">
                                            <a href="{{ route('fsk-debitur.create') }}" data-flag="debitur" title="Tambah Debitur" class="modal-show-debitur"><i class="fa fa-plus"></i></a>
                                        </span>
                                    </div>
                                </div>  
                            </div>
                            <div class="bank2" style="display: none;">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email*</label>
                                    <input type="text" readonly="true" id="email_client" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="bank3" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Perusahaan Pemohon*</label>
                                    <input type="text" readonly="true" class="form-control" id="perusahaan_pemohon" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">PIC*</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter PIC">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Head Legal*</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Invoice Ditujukan ke</label>
                      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                    </div>

                </div>
            </div>
        </div>

        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        </div>
        </div>

        <!-- Table Service -->
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Service</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('fsk-service.create') }}" id="tambah_service" class="btn btn-sm btn-success" title="Tambah Service">
                            <i class="fa fa-plus"></i> Tambah Service
                        </a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div id="hidden_attr">
                    <input type="hidden" value="1" id="Count">
                    
                </div>
                <div class="box-body">
                    
                    <div class="box box-solid">
                        <table id="datatableServiceCart" class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Service Name</th>
                                        <!-- <th>Detail</th> -->
                                        <th width="20%">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="serviceList">
                                    
                                </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>

@endsection

@push('scripts')
<script type="text/javascript">


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function () {
        $('#nama_pemohon').on("change", function (e) { 

            var id = $(this).val();

            $.ajax({
                url : '{{ route('fsk.fsk-getCategory') }}',
                method : 'POST',
                data : {'id':id},
                success : function(response){
                    // alert(response.client_flag);
                    if (response.client_flag == 'perorangan') {
                        $('.bank').hide();
                        $('.bank2').show();
                        $('.bank3').hide();
                        $('#email_client').val(response.email);

                    }else if(response.client_flag == 'perbankan'){
                        $('.bank').show();
                        $('.bank2').hide();
                        $('.bank3').show();
                        $('#perusahaan_pemohon').val(response.name);
                    }else{

                    }
                },
                error : function(e){
                    alert('Error Ajax!');
                }
            });
        });
    });

    $('#modal-btn-save-service-cart').click(function(e){
    e.preventDefault();
    
    var form = $('#modal-body-service-cart form'),
        url = form.attr('action'),
        
        // method = form.attr('method');
        method = $('input[name=_method]').val() == undefined ? 'post' : 'put';



    form.find('.help-block').remove();
    form.find('.form-group').removeClass('has-error');
    
    $.ajax({
        url : url,
        method : method,
        data : form.serialize(),
        success : function(response){
            console.log('response : '+response);
            form.trigger('reset');

            $('#modal-service-cart').modal('hide');

            if (response.status) {
                
                var counter = $('#Count').val().trim();
                var apake = parseInt(counter);
                var url = "{{ route('fsk-service.edit', ':id'  )}}";
                    url = url.replace(':id', counter);

                // Add attribute TR
                $('#serviceList').append('<tr id="tr_' + counter + '"><td>'+ counter +'</td><td id="servic_'+ counter +'">' + response.service_name + '</td><td><a id="asatu_' + counter + '" href="#" class="btn btn-xs btn-primary modal-service-detail" data-service="'+ response.service_name +'" data-keterangan="'+ response.keterangan +'" title="Detail "><i class="fa fa-eye"></i></a>|<a id="adua_' + counter + '" href="'+ url +'" class="btn btn-xs btn-primary modal-service-edit" data-id='+ counter +' data-service_id='+ response.service_id +' data-keterangan="'+ response.keterangan +'" title="Edit "><i class="fa fa-edit"></i></a>|<a href="#" class="btn btn-xs btn-danger btn-deletedAppend" data-id='+ counter +' title="Delete "><i class="fa fa-trash"></i></a></td></tr>');

                // add Element
                $('#hidden_attr').append('<input type="hidden" id="serv_' + counter + '" name="postService[]" value="' + response.service_id + '">');
                $('#hidden_attr').append('<input type="hidden" id="ket_' + counter + '" name="postKeterangan[]" value="' + response.keterangan + '">');
                $('#Count').val(++apake);

                
            
            }else{
                swal({
                    type : 'error',
                    title : 'Error 401',
                    text : 'Ajax error!'
                });
            }

            
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

    
    
</script>
@endpush