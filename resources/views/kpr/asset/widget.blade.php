<div class="row">
  <div class="col-lg-3 col-xs-6">
    <div class="small-box bg-green">
      <div class="inner">
        <h3 id="success-value">
          {{ $published }}
        </h3>

        <p><b>Published</b></p>
      </div>

      <div class="icon">
        <i class="fa fa-check"></i>
      </div>
      <a href="#" class="small-box-footer" id="published">
        Show data <i class="fa fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>

<div class="col-lg-3 col-xs-6">
  <div class="small-box bg-yellow">
    <div class="inner">
      <h3 id="success-value">
       {{ $draft }}
     </h3>

     <p><b>Draft</b></p>
   </div>
   <div class="icon">
    <i class="fa fa-file"></i>
  </div>
  <a href="#" class="small-box-footer" id="draft">
    Show data <i class="fa fa-arrow-circle-right"></i>
  </a>
</div>
</div>

<div class="col-lg-3 col-xs-6">
  <div class="small-box bg-aqua">
    <div class="inner">
      <h3 id="success-value">
       {{ $booked }}
     </h3>

     <p><b>Sold</b></p>
   </div>
   <div class="icon">
    <i class="fa fa-pencil-square-o"></i>
  </div>
  <a href="#" class="small-box-footer" id="booked">
    Show data <i class="fa fa-arrow-circle-right"></i>
  </a>
</div>
</div>
<!-- ./col -->
<div class="col-lg-3 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-red">
    <div class="inner">
      <h3 id="process-value">
        {{ $unpublish }}
      </h3>

      <p><b>Unpublish</b></p>
    </div>
    <div class="icon">
      <i class="fa fa-close"></i>
    </div>
    <a href="#" class="small-box-footer" id="un">
      Show data <i class="fa fa-arrow-circle-right"></i>
    </a>
  </div>
</div>
</div>