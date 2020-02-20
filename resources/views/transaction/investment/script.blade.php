@push('scripts')
<script>
var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();

today = yyyy + '-' + mm + '-' + dd;

window.varTableHistory;

window.table = $('#datatable').DataTable({
    responsive : true,
    processing : true,
    serverSide : true,
    ajax: "{{ route('ecommerce', [$start, $end, $filter]) }}",
    columns: [
        // {data : 'checkbox', name : 'checkbox'},
        {data : 'DT_Row_Index', name : 'id'},
        {data : 'transaction_number', name : 'transaction_number'},
        {data : 'date_transaction', name : 'date_transaction'},
        {data : 'investor_name', name : 'investor_name'},
        {data : 'total_amount', name : 'total_amount'},
        {data : 'verified', name : 'verified'},
        {data : 'total_asset', name : 'total_asset'},
        {data : 'action', name : 'action'}
    ]
});

$('#verified').on('click', function(){
    table.columns(6).search('verified').draw();
    table.columns(2).search(today).draw();
});
$('#success').on('click', function(){
    table.columns(6).search('N').draw();
    table.columns(3).search(today).draw();
});
$('#process').on('click', function(){
    table.columns(6).search('process').draw();
    table.columns(2).search(today).draw();
});
$('#rejected').on('click', function(){
    table.columns(6).search('rejected').draw();
    table.columns(2).search(today).draw();
});

$('#reservation').daterangepicker();

$(document).ready(function() {
  var SudahValidasi = 0;

  $('#search_data').click(function(e) {

    e.preventDefault();
      $('#form_filter').submit();
  });

  $('#form_filter').submit(function(e) {

    if (SudahValidasi == 0) {
      e.preventDefault();

      if($('#start').val().trim() > $('#end').val().trim()){
        swal({
            type : 'error',
            title : 'Oops something went wrong!',
            text : 'Tanggal tidak valid!'
        });
      }else{
        SudahValidasi = 1;
        $('#search_data').click();
      }

    }
  });

});



$('#btn-reload').on('click', function(){
    table.ajax.reload();
    // reloadWidget();
});
$('#btn-clear-filter').on('click', function(){
    window.location.href= "{{ route('investment.index') }}";
});

setInterval( function () {
    table.ajax.reload( null, false ); // user paging is not reset on reload
    // reloadWidget();
}, 20000 );

/* RETIREVE WIDGET VALUE */
function reloadWidget() {
    var success = document.getElementById('success-value');
    var processed = document.getElementById('process-value');
    var rejected = document.getElementById('rejected-value');
    var verified = document.getElementById('verified-value');
    $.ajax({
      url : 'widget/monitoring-cashout',
      method : 'get',
      dataType : 'json',
      success : function(r) {
        success.innerHTML = r.success;
        processed.innerHTML = r.process;
        rejected.innerHTML = r.rejected;
        verified.innerHTML = r.verified;
      },
      error : function(e) {
        console.log(e);
      }
    });
}

/* show modal */
$('body').on('click', '.btn-detail-cashout', function(e){
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'),
        id = me.data('id'), 
        status = me.data('status'); 

    var btnProcess = $('#btn-process'),
        btnVerify = $('#btn-verify'),
        btnReject = $('#btn-reject');

    $('#modal-title2').text(title);
    $('#modal-btn-save2').addClass('hide');

    $.ajax({
        url :url,
        dataType : 'html',
        success : function(response){
            $('#modal2').modal('show');
            var modalBody = $('#modal-body2').html(response);
            
            if(status == 'SUCCESS'){
              $('#btn-process').show();
              $('#btn-reject').show();
            } else if(status == 'PROCESS') {
              $('#btn-verify').show();
              $('#btn-reject').show();
            }

            varTableHistory = tableHistory(id);
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

function tableHistory(id)
{
  return $('#datatable-history').DataTable({
      
  });
}

// PROCESS
$('body').on('click', '#btn-process', function(e){
  e.preventDefault();

  var me = $(this),
      url = $(this).attr('href'),
      id = $(this).data('id');

  swal({
      title : 'Are you sure ?',
      type : 'warning',
      showCancelButton : true,
      confirmButtonColor : '#3085d6',
      cancelButtonColor : '#d33',
      confirmButtonText : 'Yes'
  }).then((result)=>{
      if(result.value){
        $.ajax({
          url : url,
          method : 'get',
          dataType: 'json',
          success : function(r) {
            if(r)
            {
                // ganti label status
                var id = document.getElementById('label-tran-status');
                id.classList.remove("label-success");
                id.classList.add("label-warning");
                id.innerHTML = "Process";
                // hiding tombol process & munculkan tombol verify dan reject
                me.hide();
                $('#btn-verify').show();
                $('#btn-reject').show();
                // reload table utama
                table.ajax.reload();
                varTableHistory.ajax.reload();
                // reloadWidget();

                swal({
                    type : 'success',
                    title : 'Success',
                    text : 'Status changed'
                });
            }
            else
            {
                swal({
                    type : 'danger',
                    title : 'Failed',
                    text : 'Error 500'
                });
            }
          },
          error : function(e) {
            swal({
                type : 'error',
                title : 'Error',
                text : 'Failed'
            });
          }
        });
      }
  });
});

// VERIFY
$('body').on('click', '#btn-verify', function(e){
  e.preventDefault();
  
  var me = $(this),
      url = $(this).attr('href'),
      id = $(this).data('id');

  swal({
      title : 'Are you sure ?',
      type : 'warning',
      showCancelButton : true,
      confirmButtonColor : '#3085d6',
      cancelButtonColor : '#d33',
      confirmButtonText : 'Yes'
  }).then((result)=>{
      if(result.value){
        $.ajax({
          url : url,
          method : 'get',
          dataType: 'json',
          success : function(r) {
              if(r)
              {
                  // ganti label status
                  var id = document.getElementById('label-tran-status');
                  id.classList.remove("label-success");
                  id.classList.add("label-primary");
                  id.innerHTML = "Verified";
                  // hiding tombol process & munculkan tombol verify dan reject
                  me.hide();
                  $('#btn-reject').hide();
                  // reload table utama
                  table.ajax.reload();
                  varTableHistory.ajax.reload();
                  // reloadWidget();

                  swal({
                      type : 'success',
                      title : 'Success',
                      text : 'Status changed'
                  });
              }
              else
              {
                  swal({
                      type : 'danger',
                      title : 'Failed',
                      text : 'Error 500'
                  });
              }
          },
          error : function(e) {
            swal({
                type : 'error',
                title : 'Error',
                text : 'Failed'
            });
          }
        });
      }
  });
});

// REJECT
$('body').on('click', '#btn-reject', function(e){
  e.preventDefault();
  
  var id = $(this).data('id');

  $('#modal2').modal('hide');
  $('#modal-reject').modal('show');
  $('#balance_out_id').val(id);
});

$('#modal-save-reject').click(function(e){
  e.preventDefault();
  
  var form = $('#reject-reason'),
      url = form.attr('action'),
      method = form.attr('method');

  form.find('.help-block').remove();
  form.find('.form-group').removeClass('has-error');

  $.ajax({
    url : url,
    method : method,
    data : form.serialize(),
    success : function(res) {
      if(res)
      {
          $('#modal-reject').modal('hide');
          table.ajax.reload();
          // reloadWidget();
          swal({
              type : 'success',
              title : 'Success',
              text : 'Saved'
          });
      }
      else
      {
          swal({
              type : 'danger',
              title : 'Failed',
              text : 'Failed'
          });
      }
    },
    error : function(e) {
      var response = e.responseJSON;
      console.log(response);
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