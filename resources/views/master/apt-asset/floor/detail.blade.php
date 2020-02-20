
<div class="box box-default">

    <div class="box-header with-border">
        <h3 class="box-title">Detail Floor</h3>
        <div class="box-tools pull-right">
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
        </div>
    </div>
            
    <div class="box-body">
        <table class="table table-bordered">
			<tr>
				<th>Code Floor</th>
				<td>{{ $model->code_floor }}</td>
			</tr>

			<tr>
				<th>Floor Name</th>
				<td>{{ $model->name }}</td>
			</tr>

			<tr>
				<th>Denah Lokasi</th>
				<td><a href="{{ asset($model->denah) }}" target="_blank"> Download Denah Lokasi <i class="fa fa-external-link"></i></a> </td>
			</tr>
		</table>
    </div>

</div>


<div class="box box-default">

    <div class="box-header with-border">
        <h3 class="box-title">Unit on this floor</h3>
        <div class="box-tools pull-right">
             <a href="{{ route('unit-asset.unitnew', base64_encode($model->code_floor)) }}" class="modal-show-unit btn btn-default pull-right" title="Add Unit">
                <i class="fa fa-plus"></i> Add
            </a>
        </div>
    </div>
            
    <div class="box-body">
        <table id="dataUnitFloor" class="table table-bordered table-hover table-condensed" style="width: 100%;">
		    <thead>
		        <tr>
		            <th width="5%">No</th>
		            <th>Unit Name</th>
		            <th width="20%">#</th>
		        </tr>
		    </thead>
		</table>
    </div>

</div>


<script type="text/javascript">
   $('#dataUnitFloor').DataTable({
        responsive : true,
        processing : true,
        serverSide : true,
        ajax: "{{ route('table.apt.floor.unit', $model->code_floor) }}",
        columns: [
            {data : 'DT_Row_Index', name : 'code_unit'},
            {data : 'name', name : 'name'},
            {data : 'action', name : 'action'}
        ],
        order : [[0, 'desc']]
    });
</script>