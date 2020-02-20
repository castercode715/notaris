
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Invoice {{ $model->no_order }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/dist/css/AdminLTE.min.css">

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body onload="window.print()" onfocus="window.close()">

<div class="wrapper">
  <!-- Main content -->
   <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> Kop-Aja, Inc.
            <small class="pull-right">Date: {{ date('d/m/Y ', strtotime($model->date_transaction)) }}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          From
          <address>
            <strong>Kop-Aja, Inc.</strong><br>
            Grand Slipi Tower, Jl. Letjen S. Parman<br>
            Palmerah, Kec. Palmerah 11480<br>
            Phone: (081) 1 1797 996<br>
            Email: admin@kop-aja.com
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          To
          <address>
            <strong>{{ $inv->full_name }}</strong><br>
            {{ $inv->address }}<br>
            Phone: {{ $inv->phone }}<br>
            Email: {{ $inv->email }}
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>Invoice #{{ $model->no_order }}</b><br>
          
        
          @if($model->no_resi != "" && $model->expedition_name != "")
          <br>
          <b>Ekspedition Name:</b> {{ $model->expedition_name }}<br>
          <b>No Resi:</b> #{{ $model->no_resi }}
          @endif
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>Qty</th>
              <th>Product</th>
              <th>Price </th>
              <th>Discount</th>
              <th>Total</th>
            </tr>
            </thead>
            <tbody>
              @foreach($detailProduct as $detail)
              <tr>
                <td>{{ $detail->qty }}</td>
                <td>{{ $model->getProduct($detail->product_id) }}</td>
                <td>Rp. {{ number_format($detail->harga) }}</td>
                <td>{{ $detail->discount }} %</td>
                <td>Rp. {{ number_format($detail->price) }}</td>
              </tr>

              
              <tr>
                <td><i></i></td>
                <td colspan="4"><i>{{ $model->getAttr($detail->id) }}</i>  </td>
              </tr>
              

              @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
          <p class="lead">Payment Methods:</p>
          <img src="{{ asset('images/visa.png')}}" alt="Visa">
          <img src="{{ asset('images/mastercard.png')}}" alt="Mastercard">
          <img src="{{ asset('images/american-express.png')}}" alt="American Express">
          <img src="{{ asset('images/paypal2.png')}}" alt="Paypal">

          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
          </p>
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <p class="lead">Payment Total:</p>

          <div class="table-responsive">
            <table class="table">
              <tbody><tr>
                <th style="width:50%">Subtotal:</th>
                <td>Rp. {{ number_format($model->total_price) }}</td>
              </tr>
              <tr>
                <th>Tax (0%)</th>
                <td>Rp. 0</td>
              </tr>
              <tr>
                <th>Shipping:</th>
                <td>Rp. 0</td>
              </tr>
              <tr>
                <th>Total:</th>
                <td>Rp. {{ number_format($model->total_price) }}</td>
              </tr>
            </tbody></table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          
          @if($status->status == "NEW")
          <button type="button" class="btn btn-success pull-right" id="btn-process-order" data-status="process" data-inv="{{ $model->investor_id }}" data-hreff="{{ route('order.log', $model->id) }}" data-title="Modal Process Order"><i class="fa fa-external-link"></i> Process this Order</button>
          @elseif($status->status == "PROCESS")
          <button type="button" class="btn btn-success pull-right" id="btn-send-order" data-status="delivery" data-inv="{{ $model->investor_id }}" data-hreff="{{ route('order.log', $model->id) }}" data-title="Modal Send Order"><i class="fa fa-external-link"></i> Send this Order</button>
          @elseif($status->status == "DELIVERY")
          <button type="button" class="btn btn-success pull-right" id="btn-confirmation-order" data-status="received" data-inv="{{ $model->investor_id }}" data-hreff="{{ route('order.log', $model->id) }}" data-title="Modal Received Order"><i class="fa fa-check"></i> Confirmation this order has been received</button>
          @endif
          <!-- <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Generate PDF
          </button> -->
        </div>
      </div>
    </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>

