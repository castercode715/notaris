<div class="box-body">
    <div class="box-header" style="margin-top: -67px;margin-right: -10px;position: unset;">
      
        <a href="{{ route('unit-asset.unitnew', $model->code_apt) }}" class="modal-show btn btn-primary pull-right" title="Add Unit">
                <i class="fa fa-plus"></i> Add unit
            </a>
        
    </div>
	<table id="dataUnit" class="table table-bordered table-hover table-condensed" style="width: 100%;">
        <thead>
            <tr>
                <!-- <th width="5%">No</th> -->
                <th>Unit Name</th>
                <th width="30%">Floor</th>
                <th width="7%">#</th>
            </tr>
        </thead>
    </table>
</div>
