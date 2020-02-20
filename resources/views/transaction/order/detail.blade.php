@extends('base.main')
@section('title') Detail Order  @endsection
@section('page_icon') <i class="fa fa-opencart"></i> @endsection
@section('page_title') Detail Order #{{ $model->no_order }} @endsection

@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            
            <a href="{{ route('order.index') }}" class="btn btn-success" title="Manage member">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs tabs-up" id="friends">
        <li>
            <a href="{{ route('order.invoice.pane') }}" data-toggle="tabajax-detail" data-target="#tab_detail" class="active" id="detail_tab" rel="tooltip"> <i class="fa fa-cubes"></i>&nbsp;&nbsp; Invoice </a>
        </li>
        <li>
            <a href="{{ route('order.history.pane', $model->id) }}" data-toggle="tabajax-unit" data-target="#tab_history" id="history_tab" rel="tooltip"> <i class="fa fa-code-fork"></i>&nbsp;&nbsp; History </a>
        </li>
    </ul>


    <div class="tab-content">
        <div class="tab-pane active" id="tab_detail">
            @include('transaction.order.tab.detail')
        </div>
        <div class="tab-pane" id="tab_history">
            @include('transaction.order.tab.history')
        </div>
        
    </div>
</div>
    
@endsection
@push('scripts')
  <script type="text/javascript">

    $('body').on('click', '#btn-send-order', function(e){
      e.preventDefault();

      var status = $(this).data('status'),
          url = $(this).data('hreff'),
          inv = $(this).data('inv'),
          title = $(this).data('title');

      $('#modal-title-log').text(title);

      swal({
          title : 'Are you sure want to send this order?',
          type : 'warning',
          showCancelButton : true,
          confirmButtonColor : '#3085d6',
          cancelButtonColor : '#d33',
          confirmButtonText : 'Yes!'
      }).then((result)=>{
          if(result.value){
            $.ajax({
                url :url,
                dataType : 'html',
                success : function(response){

                    $('#modal-log-order').modal('show');
                    $('#modal-body-log').html(response);
                    document.getElementById('status_log').value = status;

                    document.getElementById('investor_id').value = inv;

                    $('#conten').show();
                    
                },
                error : function(error)
                {
                    swal({
                        type : 'error',
                        title : 'Error 401',
                        text : 'Ajax error!'
                    });
                }
            });
            
          }
      });

    });

    $('body').on('click', '#btn-process-order', function(e){
      e.preventDefault();

      var status = $(this).data('status'),
          url = $(this).data('hreff'),
          inv = $(this).data('inv'),
          title = $(this).data('title');

      $('#modal-title-log').text(title);

      swal({
          title : 'Are you sure want to process this order?',
          type : 'warning',
          showCancelButton : true,
          confirmButtonColor : '#3085d6',
          cancelButtonColor : '#d33',
          confirmButtonText : 'Yes!'
      }).then((result)=>{
          if(result.value){
            $.ajax({
                url :url,
                dataType : 'html',
                success : function(response){

                    $('#modal-log-order').modal('show');
                    $('#modal-body-log').html(response);

                    document.getElementById('status_log').value = status;
                    document.getElementById('no_resi').value = "Empty";
                    document.getElementById('ex_name').value = "Empty";
                    document.getElementById('investor_id').value = inv;
                    $('#conten').hide();
                },
                error : function(error)
                {
                    swal({
                        type : 'error',
                        title : 'Error 401',
                        text : 'Ajax error!'
                    });
                }
            });
            
          }
      });

    });


    $('body').on('click', '#btn-confirmation-order', function(e){
      e.preventDefault();

      var status = $(this).data('status'),
          url = $(this).data('hreff'),
          inv = $(this).data('inv'),
          title = $(this).data('title');

      $('#modal-title-log').text(title);

      swal({
          title : 'Are you sure this order has been received ?',
          type : 'warning',
          showCancelButton : true,
          confirmButtonColor : '#3085d6',
          cancelButtonColor : '#d33',
          confirmButtonText : 'Yes!'
      }).then((result)=>{
          if(result.value){
            $.ajax({
                url :url,
                dataType : 'html',
                success : function(response){

                    $('#modal-log-order').modal('show');
                    $('#modal-body-log').html(response);

                    document.getElementById('status_log').value = status;
                    document.getElementById('no_resi').value = "Empty";
                    document.getElementById('ex_name').value = "Empty";
                    document.getElementById('investor_id').value = inv;
                    $('#conten').hide();
                },
                error : function(error)
                {
                    swal({
                        type : 'error',
                        title : 'Error 401',
                        text : 'Ajax error!'
                    });
                }
            });
            
          }
      });

    });

    $(document).ready(function(){
      $.ajax({ url: "{{ route('order.get-status.pane', $model->id) }}",
              context: document.body,
              success: function(data){
                document.getElementById('status_value').value = data.data;
              }});
    });
  
    $('[data-toggle="tabajax-unit"]').click(function(e) {
      var $this = $(this),
          loadurl = $this.attr('href'),
          targ = $this.attr('data-target');

      $.get(loadurl, function(data) {
          $(targ).html(data);

          $('#dataHistoryOrderStatus').DataTable({
              responsive : true,
              processing : true,
              serverSide : true,
              ajax: "{{ route('table.history.order', $model->id) }}",
              columns: [
                  {data : 'DT_Row_Index', name : 'id'},
                  {data : 'status', name : 'status'},
                  {data : 'date', name : 'date'},
                  {data : 'note', name : 'note'},
                  {data : 'by', name : 'by'}
              ],
              order : [[0, 'desc']]
          });
      });

      $this.tab('show');
      return false;
    });

    $('[data-toggle="tabajax-detail"]').click(function(e) {
      var $this = $(this),
          loadurl = $this.attr('href'),
          targ = $this.attr('data-target');


      $.ajax({ url: "{{ route('order.get-status.pane', $model->id) }}",
              context: document.body,
              success: function(data){
                document.getElementById('status_value').value = data.data;
              }});

        $this.tab('show');
        return false;
    });
  </script>

@endpush

