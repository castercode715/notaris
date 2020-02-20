<div style="width: 100%; overflow-x: scroll;">
    <div class="row" style="width: 2000px;">
        <div class="col-md-5 row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3 id="success-value">{{ $success }}</h3>

                  <p><b>New</b></p>
                </div>
                <div class="icon">
                  <i class="fa fa-bell"></i>
                </div>
                <a href="javascript:void(0)" class="small-box-footer" id="success" data-value="new">
                  Show data <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3 id="pending-value">{{ $pending }}</h3>

                  <p><b>Pending</b></p>
                </div>
                <div class="icon">
                  <i class="fa fa-clock-o"></i>
                </div>
                <a href="javascript:void(0)" class="small-box-footer" id="pending" data-value="pending">
                  Show data <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3 id="verified-value">{{ $verified }}</h3>
                  <p><b>Verified</b></p>
                </div>
                <div class="icon">
                  <i class="fa fa-check"></i>
                </div>
                <a href="javascript:void(0)" class="small-box-footer" id="verified" data-value="verified">
                  Show data <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3 id="failed-value">{{ $failed }}</h3>

                  <p><b>Failed</b></p>
                </div>
                <div class="icon">
                  <i class="fa fa-ban"></i>
                </div>
                <a href="javascript:void(0)" class="small-box-footer" id="failed" data-value="failed">
                  Show data <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
        </div>
        <div class="col-md-5 row" style="">
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-maroon">
                  <div class="inner">
                      <h3 id="rejected-value">{{ $rejected }}</h3>

                      <p><b>Rejected</b></p>
                  </div>
              <div class="icon">
                  <i class="fa fa-close"></i>
              </div>
              <a href="javascript:void(0)" class="small-box-footer" id="rejected" data-value="rejected">
                  Show data <i class="fa fa-arrow-circle-right"></i>
              </a>
              </div>
            </div>
            <div class="col-lg-3 col-xs-6" style="border-right: 1px solid #ddd;">
              <div class="small-box bg-purple">
                  <div class="inner">
                      <h3 id="total-value">{{ $total }}</h3>
                      <p><b>Total</b></p>
                  </div>
              <div class="icon">
                  <i class="fa fa-cube"></i>
              </div>
                <a href="javascript:void(0)" class="small-box-footer" id="total" data-value="total">
                    Show data <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
                
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-orange">
                    <div class="inner">
                        <h3 id="method-tm-value">{{ $tm }}</h3>

                        <p><b>Transfer Manual</b></p>
                    </div>
                <div class="icon">
                    <i class="fa fa-money"></i>
                </div>
                <a href="javascript:void(0)" class="small-box-footer" id="method-tm" data-value="tm">
                    Show data <i class="fa fa-arrow-circle-right"></i>
                </a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-teal">
                    <div class="inner">
                        <h3 id="method-md-value">{{ $md }}</h3>

                        <p><b>Midtrans</b></p>
                    </div>
                <div class="icon">
                    <i class="fa fa-star"></i>
                </div>
                <a href="javascript:void(0)" class="small-box-footer" id="method-md" data-value="md">
                    Show data <i class="fa fa-arrow-circle-right"></i>
                </a>
                </div>
            </div>
        </div>
        <div class="col-md-2 row" style="">
            <div class="col-lg-8 col-xs-6">
                <div class="small-box bg-blue">
                    <div class="inner">
                        <h3 id="method-pp-value">{{ $pp }}</h3>

                        <p><b>PayPal</b></p>
                    </div>
                <div class="icon">
                    <i class="fa fa-paypal"></i>
                </div>
                <a href="javascript:void(0)" class="small-box-footer" id="method-pp" data-value="pp">
                    Show data <i class="fa fa-arrow-circle-right"></i>
                </a>
                </div>
            </div>
        </div>
    </div>
</div>