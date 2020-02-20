$('body').on('click', '.modal-show', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'),
        id = me.data('id');
    
    $('#modal-title').text(title);
    $('#modal-btn-save').removeClass('hide').text(me.hasClass('edit') ? 'Update' : 'Create');
    
    $.ajax({
        url: url,
        dataType: 'html',
        success: function(response)
        {
            $('#modal').modal('show');
            $('#modal-body').html(response);
            
            $('input[name=investor_id]').val(id);
            let modal = $('.modal');
            if (hasClass(modal, 'datepicker')) {
                $('.datepicker').datepicker({
                    autoclose: true,
                    format : 'dd-mm-yyyy'
                })
            }
        },
        error : function(error)
        {
            $('#modal').modal('hide');
            swal({
                type : 'error',
                title : 'Error 401',
                text : 'Unauthorized action'
            });
        }
    });

});


function hasClass(element, cls) {
    return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
}

$('body').on('click', '.modal-show-unit', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'),
        id = me.data('id');
    
    $('#modal-title-unit').text(title);
    $('#modal-btn-save-unit').removeClass('hide').text(me.hasClass('edit') ? 'Update' : 'Create');
    
    $.ajax({
        url: url,
        dataType: 'html',
        success: function(response)
        {
            $('#modal-unit').modal('show');
            $('#modal2').modal('hide');
            $('#modal-body-unit').html(response);
            
            $('input[name=investor_id]').val(id);
        },
        error : function(error)
        {
            $('#modal').modal('hide');
            swal({
                type : 'error',
                title : 'Error 401',
                text : 'Unauthorized action'
            });
        }
    });

});

$('#modal-btn-save').click(function(e){
    e.preventDefault();

    // console.log($('input[name=_method]').val());
    
    var form = $('#modal-body form'),
        url = form.attr('action'),
        // method = form.attr('method');
        method = $('input[name=_method]').val() == undefined ? 'post' : 'put';

    // console.log(method);

    form.find('.help-block').remove();
    form.find('.form-group').removeClass('has-error');
    
    $.ajax({
        url : url,
        method : method,
        data : form.serialize(),
        success : function(response){
            console.log('response : '+response);
            form.trigger('reset');
            $('#modal').modal('hide');
            $('#datatable').DataTable().ajax.reload();
            $('#dataUnit').DataTable().ajax.reload();
            $('#datatable-internet_banking').DataTable().ajax.reload();
            $('#datatable-atm').DataTable().ajax.reload();
            // location.reload(); 
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

$('#modal-btn-save-unit').click(function(e){
    e.preventDefault();

    // console.log($('input[name=_method]').val());
    
    var form = $('#modal-body-unit form'),
        url = form.attr('action'),
        method = form.attr('method');
        // method = $('input[name=_method]').val() == undefined ? 'post' : 'put';

    // console.log(method);

    form.find('.help-block').remove();
    form.find('.form-group').removeClass('has-error');
    
    $.ajax({
        url : url,
        method : method,
        data : form.serialize(),
        success : function(response){
            console.log('response : '+response);
            form.trigger('reset');
            $('#modal-unit').modal('hide');
            $('#modal2').modal('show');
            $('#dataUnitFloor').DataTable().ajax.reload();

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

$('#modal-btn-save-category').click(function(e){
    e.preventDefault();
    
    var form = $('#modal-category form'),
        url = form.attr('action'),
        method = form.attr('method');

    form.find('.help-block').remove();
    form.find('.form-group').removeClass('has-error');
    
    $.ajax({
        url : url,
        method : method,
        data : form.serialize(),
        success : function(response){
            $('#modal-category').modal('hide');
            location.reload(); 

            swal({
                type : 'success',
                title : 'Success',
                text : 'Saved'
            });
        },
        error : function(e){
            swal({
                type : 'error',
                title : 'Error Ajax',
                text : 'Something wrong way!'
            });
        }
    });

});

$('body').on('click', '#btn-proses-order', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.attr('data-href'),
        title = me.attr('title'),
        csrf_token = $('meta[name="csrf-token"]').attr('content');

     swal({
        title : 'Are you sure want to process ?',
        type : 'warning',
        showCancelButton : true,
        confirmButtonColor : '#3085d6',
        cancelButtonColor : '#d33',
        confirmButtonText : 'Ya, hapus!'
    }).then((result)=>{
        if(result.value){
            $.ajax({
                url : url,
                type : 'get',
                success : function(r){

                    swal({
                        type : 'success',
                        title : 'Success',
                        text : 'The order was successfully processed!'
                    });
                },
                error : function(er){
                    swal({
                        type : 'error',
                        title : 'Error 401',
                        text : 'Error Ajax'
                    }); 
                }
            });
        }
    });

});


$('body').on('click', '.btn-delete', function(e){
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
                    $('#datatable').DataTable().ajax.reload();
                    $('#dataUnit').DataTable().ajax.reload();
                    $('#dataUnitFloor').DataTable().ajax.reload();
                    $('#datatable-internet_banking').DataTable().ajax.reload();
                    $('#datatable-atm').DataTable().ajax.reload();
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
                    else if(er.status == 400)
                    {
                        swal({
                            type : 'error',
                            title : 'Failed',
                            text : 'Table has related!'
                        });

                    }else{

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

$('body').on('click', '.btn-delete2', function(e){
    e.preventDefault();

    var link = $(this).attr('href');

    swal({
        title : 'Are you sure ?',
        type : 'warning',
        showCancelButton : true,
        confirmButtonColor : '#3085d6',
        cancelButtonColor : '#d33',
        confirmButtonText : 'Ya, hapus!'
    }).then((result)=>{
        if(result.value){
            window.location = link;
        }
    });

});

$('body').on('click', '.btn-delete3', function(e){
    e.preventDefault();

    var link = $(this).attr('href');

    swal({
        title : 'Are you sure ?',
        type : 'warning',
        showCancelButton : true,
        confirmButtonColor : '#3085d6',
        cancelButtonColor : '#d33',
        confirmButtonText : 'Ya, hapus!'
    }).then((result)=>{
        if(result.value){
            $.ajax({
                url : link,
                type : 'get',
                success : function(r){
                    $('#datatable').DataTable().ajax.reload();
                    $('#datatable-internet_banking').DataTable().ajax.reload();
                    $('#datatable-atm').DataTable().ajax.reload();
                    
                    swal({
                        type : 'success',
                        title : 'Success',
                        text : 'Data berhasil dihapus'
                    });
                },
                error : function(er){
                    swal({
                        type : 'error',
                        title : 'Failed',
                        text : 'Data gagal dihapus'
                    });
                }
            });
        }
    });

});

$('body').on('click', '.btn-show', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'); 

    $('#modal-title').text(title);
    $('#modal-btn-save').addClass('hide');

    $.ajax({
        url :url,
        dataType : 'html',
        success : function(response){
            $('#modal').modal('show');
            $('#modal-body').html(response);
        },
        error : function(error)
        {
            $('#modal').modal('hide');
            swal({
                type : 'error',
                title : 'Error 401',
                text : 'Unauthorized action'
            });
        }
    });

});

$('body').on('click', '#btn-show-detail-order', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'); 

    $('#modal-title-order2').text(title);

    $.ajax({
        url :url,
        dataType : 'html',
        success : function(response){
            $('#modal-order').modal('hide');
            $('#modal-detail-order').modal('show');
            $('#modal-body-order2').html(response);
        },
        error : function(error)
        {
            $('#modal-detail-order').modal('hide');
            swal({
                type : 'error',
                title : 'Error 401',
                text : 'Unauthorized action'
            });
        }
    });

});

$('body').on('click', '#btn-back', function(e){
    e.preventDefault();

    $('#modal-detail-order').modal('hide');
    $('#modal-order').modal('show');
});

$('body').on('click', '.btn-show-unit', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'); 

    $('#modal-title-unit').text(title);
    $('#modal-btn-save-unit').addClass('hide');

    $.ajax({
        url :url,
        dataType : 'html',
        success : function(response){
            $('#modal-unit').modal('show');
            $('#modal2').modal('hide');
            $('#modal-body-unit').html(response);
        },
        error : function(error)
        {
            $('#modal-unit').modal('hide');
            swal({
                type : 'error',
                title : 'Error 401',
                text : 'Unauthorized action'
            });
        }
    });

});

// INVESTOR
$('body').on('click', '.inv-modal-show', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'),
        id = me.data('id');
    
    $('#inv-modal-title').text(title);
    $('#inv-modal-btn-save').removeClass('hide').text(me.hasClass('edit') ? 'Update' : 'Create');
    
    $.ajax({
        url: url,
        dataType: 'html',
        success: function(response)
        {
            $('#inv-modal-body').html(response);
            $('input[name=investor_id]').val(id);
        }
    });

    $('#inv-modal').modal('show');
});

$('#inv-modal-btn-save').click(function(e){
    e.preventDefault();
    
    var form = $('#modal-body form'),
        url = form.attr('action'),
        method = 'post';

    form.find('.help-block').remove();
    form.find('.form-group').removeClass('has-error');
    
    $.ajax({
        url : url,
        method : method,
        data : form.serialize(),
        success : function(response){
            form.trigger('reset');
            $('#inv-modal').modal('hide');
            $('#datatable-internet_banking').DataTable().ajax.reload();
            $('#datatable-atm').DataTable().ajax.reload();

            swal({
                type : 'success',
                title : 'Success',
                text : 'Data berhasil disimpan'
            });
        },
        error : function(e){
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


/* *********************************
* Large Modal
* *********************************/
$('body').on('click', '.modal-show2', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'),
        id = me.data('id');
    console.log(url);
    console.log(title);
    console.log(id);
    
    $('#modal-title2').text(title);
    $('#modal-btn-save2').removeClass('hide').text(me.hasClass('edit') ? 'Update' : 'Create');
    
    $.ajax({
        url: url,
        dataType: 'html',
        success: function(response)
        {
            $('#modal2').modal('show');
            $('#modal-body2').html(response);
            $('input[name=investor_id]').val(id);
        },
        error : function(error)
        {
            $('#modal').modal('hide');
            swal({
                type : 'error',
                title : 'Error 401',
                text : 'Unauthorized action'
            });
        }
    });

});

$('body').on('click', '.modal-show-order', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title');

    console.log(url);
    console.log(title);
    
    $('#modal-title-order').text(title);
    // $('#modal-btn-save2').removeClass('hide').text(me.hasClass('edit') ? 'Update' : 'Create');
    
    $.ajax({
        url: url,
        dataType: 'html',
        success: function(response)
        {
            $('#modal-order').modal('show');
            $('#modal-body-order').html(response);
            $('input[name=investor_id]').val(id);
        },
        error : function(error)
        {
            $('#modal-order').modal('hide');
            swal({
                type : 'error',
                title : 'Error 401',
                text : 'Unauthorized action'
            });
        }
    });

});

$('#modal-btn-save2').click(function(e){
    e.preventDefault();

    // console.log($('input[name=_method]').val());
    
    var form = $('#modal-body2 form'),
        url = form.attr('action'),
        method = form.attr('method');
        // method = $('input[name=_method]').val() == undefined ? 'post' : 'put';

    // console.log(method);

    form.find('.help-block').remove();
    form.find('.form-group').removeClass('has-error');
    
    $.ajax({
        url : url,
        method : method,
        data : form.serialize(),
        success : function(response){
            console.log('response : '+response);
            form.trigger('reset');
            $('#modal2').modal('hide');
            $('#datatable').DataTable().ajax.reload();
            $('#dataUnit').DataTable().ajax.reload();
            $('#datatable-internet_banking').DataTable().ajax.reload();
            $('#datatable-atm').DataTable().ajax.reload();

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

$('body').on('click', '.btn-show2', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'); 

    $('#modal-title2').text(title);
    $('#modal-btn-save2').addClass('hide');

    $.ajax({
        url :url,
        dataType : 'html',
        success : function(response){
            $('#modal2').modal('show');
            $('#modal-body2').html(response);
        },
        error : function(error)
        {
            $('#modal2').modal('hide');
            swal({
                type : 'error',
                title : 'Error 401',
                text : 'Unauthorized action'
            });
        }
    });

});



$('#modal-btn-save-log').click(function(e){
    e.preventDefault();
    
    var form = $('#modal-body-log form'),
        url = form.attr('action'),
        method = form.attr('method');
    
    $.ajax({
        url : url,
        method : method,
        data : form.serialize(),
        success : function(response){

            $('#modal-log-order').modal('hide');
            
            swal({
                type : 'success',
                title : 'Success',
                text : 'Saved'
            });

            location.reload(); 
        },
        error : function(e){
           // $('#modal-log-order').modal('hide');

            swal({
                    type : 'error',
                    title : 'Error!',
                    text : 'Silahkan lengkapi Form!'
                });
        }
    });
});



/* =======================================================
                AJAX NOTARIS
========================================================*/
$('body').on('click', '.modal-show-fsk', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'),
        id = me.data('id');

    $('#modal-title-fsk').text(title);
    $('#modal-btn-save-fsk').removeClass('hide').text(me.hasClass('edit') ? 'Update' : 'Create');
    
    $.ajax({
        url: url,
        dataType: 'html',
        success: function(response)
        {
            $('#modal-fsk').modal('show');
            $('#modal-body-fsk').html(response);
            
            $('input[name=investor_id]').val(id);
            let modal = $('.modal');
            if (hasClass(modal, 'datepicker')) {
                $('.datepicker').datepicker({
                    autoclose: true,
                    format : 'dd-mm-yyyy'
                })
            }
        },
        error : function(error)
        {
            $('#modal-fsk').modal('hide');
            swal({
                type : 'error',
                title : 'Error 401',
                text : 'Unauthorized action'
            });
        }
    });
});

$('body').on('click', '.modal-service-detail', function(e){
    e.preventDefault();

    var me = $(this),
        service = me.data('service'),
        title = me.data('title'),
        keterangan = me.data('keterangan');

    $('#modal-title-service-detail').text(title);
    $('#modal-service-detail').modal('show');

    $('#service_value').text(service);
    $('#keterangan_value').text(keterangan);

});

$('body').on('click', '#tambah_service', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'),
        id = me.data('id');

    $('#modal-title-service-cart').text(title);
    $('#modal-btn-save-service-cart').removeClass('hide').text(me.hasClass('edit') ? 'Update' : 'Add Service');
        
    if ($('#nama_pemohon').val() == '0') {
            swal({
                type : 'warning',
                title : 'Warning',
                text : 'Silahkan lengkapi data customer terlebih dahulu !'
            });

    }else{

        $.ajax({
            url: url,
            dataType: 'html',
            success: function(response)
            {
                $('#modal-service-cart').modal('show');
                $('#modal-body-service-cart').html(response);
                
                $('input[name=investor_id]').val(id);
                let modal = $('.modal');
                if (hasClass(modal, 'datepicker')) {
                    $('.datepicker').datepicker({
                        autoclose: true,
                        format : 'dd-mm-yyyy'
                    })
                }
            },
            error : function(error)
            {
                $('#modal-service-cart').modal('hide');
                swal({
                    type : 'error',
                    title : 'Error 401',
                    text : 'Unauthorized action'
                });
            }
        });
    }

});

$('body').on('click', '.modal-show-debitur', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'),
        id = me.data('id');

    $('#modal-title-debitur').text(title);
    $('#modal-btn-save-debitur').removeClass('hide').text(me.hasClass('edit') ? 'Update' : 'Create');
    
    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'html',
        success: function(response)
        {

            $('#modal-debitur').modal('show');
            $('#modal-body-debitur').html(response);
            
            $('input[name=investor_id]').val(id);
            let modal = $('.modal');
            if (hasClass(modal, 'datepicker')) {
                $('.datepicker').datepicker({
                    autoclose: true,
                    format : 'dd-mm-yyyy'
                })
            }
        },
        error : function(error)
        {
            $('#modal-debitur').modal('hide');
            swal({
                type : 'error',
                title : 'Error 401',
                text : 'Unauthorized action'
            });
        }
    });
});

$('#modal-btn-save-debitur').click(function(e){
    e.preventDefault();
    
    var form = $('#modal-body-debitur form'),
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
            $('#modal-debitur').modal('hide');

            if (response.status) {
                $('#debitur').append('<option value="'+response.id+'" selected>'+response.name+'</option>');
                $('#debitur').select2('destroy').select2();
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

$('#modal-btn-save-fsk').click(function(e){
    e.preventDefault();
    
    var form = $('#modal-body-fsk form'),
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
            $('#modal-fsk').modal('hide');

            if (response.status) {
                $('#nama_pemohon').append('<option value="'+response.id+'" selected>'+response.name+'</option>');
                $('#nama_pemohon').select2('destroy').select2();
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

$('body').on('click', '.btn-deletedAppend', function(e){
    e.preventDefault();

    var me = $(this),
        id = me.data('id')

    swal({
        title : 'Are you sure ?',
        type : 'warning',
        showCancelButton : true,
        confirmButtonColor : '#3085d6',
        cancelButtonColor : '#d33',
        confirmButtonText : 'Ya, hapus!'
    }).then((result)=>{
        if(result.value){
            $('#tr_' + id).remove();
            $('#serv_' + id).remove();
            $('#ket_' + id).remove();

            var counter = $('#Count').val().trim();
            var apake = parseInt(counter);
            $('#Count').val(--apake);
        }
    });

});

$('body').on('click', '.modal-service-edit', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        service_id = me.data('service_id'),
        keterangan = me.data('keterangan'),
        id = me.data('id');

    $.ajax({
        url: url,
        method: 'GET',
        data : {'id': id, 'service_id': service_id, 'keterangan': keterangan},
        dataType: 'html',
        success: function(response)
        {

            $('#modal-service-edit').modal('show');
            $('#modal-body-service-edit').html(response);
            
            $('input[name=investor_id]').val(id);
            let modal = $('.modal');
            if (hasClass(modal, 'datepicker')) {
                $('.datepicker').datepicker({
                    autoclose: true,
                    format : 'dd-mm-yyyy'
                })
            }
        },
        error : function(error)
        {
            $('#modal-service-edit').modal('hide');
            swal({
                type : 'error',
                title : 'Error 401',
                text : 'Unauthorized action'
            });
        }
    });
});



