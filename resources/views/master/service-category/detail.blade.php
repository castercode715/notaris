<table class="table table-bordered">
	<tr>
		<th width="30%">Category Name</th>
		<td>{{ $service->category }}</td>
	</tr>
</table>
<p class="note"><i>Related services from this category.</i></p>
<table width="100%" id="datatable-related" class="table table-hover table-bordered">
    <thead>
        <tr>
            <!-- <th width="1%">No</th> -->
            <th>Service Name</th>
        </tr>
    </thead>
</table>

 <script>
    $('#datatable-related').DataTable({
        responsive : true,
        processing : true,
        serverSide : true,
        searching : false,
        bLengthChange : false,
        ajax: "{{ route('table.category-related', $service->id) }}",
        columns: [
            // {data : 'checkbox', name : 'checkbox'},
            // {data : 'DT_Row_Index', name : 'id'},
            {data : 'service', name : 'service'}
        ]
    });
</script>