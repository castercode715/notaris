@push('scripts')
<script>
document.body.classList.remove("sidebar-mini-expand-feature");
document.body.classList.add("sidebar-collapse");
updateLastReloadDate();
var SudahValidasi = 0;

$('#form_filter').submit(function(e) {

    if (SudahValidasi == 0) {
      e.preventDefault();

      if($('#tgl_awal').val().trim() > $('#tgl_ahir').val().trim()){
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


var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();

today = yyyy + '-' + mm + '-' + dd;

var table = $('#datatable').DataTable({
    responsive : true,
    processing : true,
    serverSide : true,
    ajax: "{{ route('table.MonitoringTopup', [$Posttgl_awal, $Posttgl_ahir]) }}",
    columns: [
        {data : 'transaction_number', name : 'transaction_number'},
        {data : 'date', name : 'date'},
        {data : 'investor', name : 'investor'},
        {data : 'amount', name : 'amount'},
        {data : 'method', name : 'method'},
        {data : 'status', name : 'status'},
        {data : 'action', name : 'action'}
    ]
});

$('#verified').on('click', function(){
    clearFilter();

    table.columns(5).search($(this).data('value')).draw();
    table.columns(1).search(today).draw();
});
$('#success').on('click', function(){
    clearFilter();
    table.columns(5).search($(this).data('value')).draw();
    table.columns(1).search(today).draw();
});
$('#pending').on('click', function(){
    clearFilter();
    table.columns(5).search($(this).data('value')).draw();
    table.columns(1).search(today).draw();
});
$('#failed').on('click', function(){
    clearFilter();
    table.columns(5).search($(this).data('value')).draw();
    table.columns(1).search(today).draw();
});
$('#rejected').on('click', function(){
    clearFilter();
    table.columns(5).search($(this).data('value')).draw();
    table.columns(1).search(today).draw();
});

$('#total').on('click', function(){
    clearFilter();   
});

$('#method-md').on('click', function(){
    clearFilter();
    table.columns(4).search($(this).data('value')).draw();
});
$('#method-pp').on('click', function(){
    clearFilter();
    table.columns(4).search($(this).data('value')).draw();
});
$('#method-tm').on('click', function(){
    clearFilter();
    table.columns(4).search($(this).data('value')).draw();
});

$('#btn-reload').on('click', function(){
    table.ajax.reload();
    reloadWidget();
    updateLastReloadDate();
});
$('#btn-clear-filter').on('click', function(){
    // clearFilter();
    window.location.href= "{{ route('monitoring-topup.index') }}";
});

function clearFilter() {
  table.columns(5).search("").draw();
  table.columns(4).search("").draw();
  table.columns(1).search("").draw();
  reloadWidget(); 
}

function updateLastReloadDate() {
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth()+1; //January is 0!
  var yyyy = today.getFullYear();
  var hour = today.getHours()<10 ? '0'+today.getHours() : today.getHours(),
      minute = today.getMinutes()<10 ? '0'+today.getMinutes() : today.getMinutes(),
      second = today.getSeconds()<10 ? '0'+today.getSeconds() : today.getSeconds();

  if(dd<10) {
    dd = '0'+dd
  } 

  if(mm<10) {
      mm = '0'+mm
  }

  today = dd + '/' + mm + '/' + yyyy+' '+hour+':'+minute+':'+second;
  document.getElementById('box-last-reload').innerHTML = '<i class="fa fa-clock-o"></i> '+today+' WIB';
}

setInterval( function () {
  table.ajax.reload( null, false ); // user paging is not reset on reload
  reloadWidget();
  updateLastReloadDate();
}, 20000 );

/* show modal */
$('body').on('click', '.btn-detail-topup', function(e){
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
            
            if(status == 'PENDING')
            {
                $('#btn-verify').show();
                $('#btn-reject').show();
            }
            else if(status == 'REJECTED')
            {
                $('#btn-recheck').show();
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
      responsive : true,
      processing : true,
      serverSide : true,
      pageLength : 5,
      ajax: "table/monitoring-topup-history/"+id,
      columns: [
          {data : 'created_at', name : 'created_at'},
          {data : 'created_by', name : 'created_by'},
          {data : 'status', name : 'status'},
          {data : 'information', name : 'information'},
          {data : 'transfer_receipt', name : 'transfer_receipt'}
      ]
  });
}

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
          success : function(r) {
            // ganti label status
            var id = document.getElementById('label-tran-status');
            id.classList.remove("label-warning");
            id.classList.add("label-primary");
            id.innerHTML = "Verified";
            // hiding tombol process & munculkan tombol verify dan reject
            me.hide();
            $('#btn-reject').hide();
            // reload table utama
            table.ajax.reload();
            varTableHistory.ajax.reload();
            reloadWidget();

            swal({
                type : 'success',
                title : 'Success',
                text : 'Status changed'
            });
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
  $('#balance_in_id_reject').val(id);
  // alert(id);
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
      form.trigger('reset');
      $('#modal-reject').modal('hide');
      table.ajax.reload();
      reloadWidget();
      swal({
          type : 'success',
          title : 'Success',
          text : 'Saved'
      });
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

// RECHECK
$('body').on('click', '#btn-recheck', function(e){
  e.preventDefault();
  
  var id = $(this).data('id');

  $('#modal2').modal('hide');
  $('#modal-recheck').modal('show');
  $('#balance_in_id_recheck').val(id);
  
});

$('#modal-save-recheck').click(function(e){
  e.preventDefault();
  
  var form = $('#recheck-reason'),
      url = form.attr('action'),
      method = form.attr('method');

  form.find('.help-block').remove();
  form.find('.form-group').removeClass('has-error');

  $.ajax({
    url : url,
    method : method,
    data : form.serialize(),
    success : function(res) {
      form.trigger('reset');
      $('#modal-recheck').modal('hide');
      table.ajax.reload();
      reloadWidget();
      swal({
          type : 'success',
          title : 'Success',
          text : 'Saved'
      });
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

/* RETIREVE WIDGET VALUE */
function reloadWidget() {
    var success = document.getElementById('success-value');
    var pending = document.getElementById('pending-value');
    var failed = document.getElementById('failed-value');
    var verified = document.getElementById('verified-value');
    var rejected = document.getElementById('rejected-value');
    var total = document.getElementById('total-value');
    var md = document.getElementById('method-md-value');
    var pp = document.getElementById('method-pp-value');
    var tm = document.getElementById('method-tm-value');

    $.ajax({
      url : 'widget/monitoring-topup',
      method : 'get',
      dataType : 'json',
      success : function(r) {
        success.innerHTML = r.success;
        pending.innerHTML = r.pending;
        failed.innerHTML = r.failed;
        verified.innerHTML = r.verified;
        rejected.innerHTML = r.rejected;
        total.innerHTML = r.total;
        md.innerHTML = r.md;
        pp.innerHTML = r.pp;
        tm.innerHTML = r.tm;
      },
      error : function(e) {
        console.log(e);
      }
    });
}

</script>
@endpush