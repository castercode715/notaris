<div class="row">
  <div class="col-lg-3 col-xs-6">
    <div class="small-box bg-green">
      <div class="inner">
        <h3 id="success-value">{{ $model->countStatus('NEW') }}</h3>

        <p><b>New</b></p>
      </div>
      <div class="icon">
        <i class="fa fa-bell"></i>
      </div>
      <a href="javascript:void(0)" class="small-box-footer" id="new">
        Show data <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-orange">
      <div class="inner">
        <h3 id="process-value">{{ $model->countStatus('PROCESS') }}</h3>

        <p><b>Process</b></p>
      </div>
      <div class="icon">
        <i class="fa fa-tasks"></i>
      </div>
      <a href="javascript:void(0)" class="small-box-footer" id="process">
        Show data <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3 id="verified-value">{{ $model->countStatus('DELIVERY') }}</h3>

        <p><b>Delivery</b></p>
      </div>
      <div class="icon">
        <i class="fa fa-truck"></i>
      </div>
      <a href="javascript:void(0)" class="small-box-footer" id="delivery">
        Show data <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-red">
      <div class="inner">
        <h3 id="rejected-value">{{ $model->countStatus('RECEIVED') }}</h3>

        <p><b>Received</b></p>
      </div>
      <div class="icon">
        <i class="fa fa-check"></i>
      </div>
      <a href="javascript:void(0)" class="small-box-footer" id="received">
        Show data <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  
</div>
