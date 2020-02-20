<div class="box-body">
    <div class="box-header" style="margin-top: -67px;margin-right: -10px;position: unset;">
      
        <a href="{{ route('floor.unit.create', base64_encode($model->code_apt)) }}" class="modal-show2 btn btn-primary pull-right" title="Add Floor">
                <i class="fa fa-plus"></i> Add Floor
            </a>
        
    </div>
	<table id="dataUnit" class="table table-bordered table-hover table-condensed" style="width: 100%;">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Floor Name</th>
                <th width="10%">#</th>
            </tr>
        </thead>
    </table>
</div>