
          
            
                      <table class="table table-bordered">
            						<tr>
            							<th width="30%"> Service Name</th>
            							<td>{{ $model->name }}</td>
            						</tr>
            						<tr>
            							<th width="30%"> Category</th>
            							<td>
                            @if(count($category) > 1 )
                            <ul style="margin-left: -22px;">
                            @foreach($category as $row)
                                <li>{{ $row->category }}</li>
                            @endforeach
                            </ul>
                            @else
                              @foreach($category as $row)
                                {{ $row->category }}
                            @endforeach
                            @endif
                          </td>
            						</tr>
            						
            					</table>

                      <p>Related documents from this service.</p>

                      <table width="100%" id="datatable-related" class="table table-hover table-bordered">
        						    <thead>
        						        <tr>
        						            <th width="1%">No</th>
        						            <th>Document Name</th>
        						        </tr>
        						    </thead>
						          </table>

                   
            <!-- /.box-body -->
         
<script>
    $('#datatable-related').DataTable({
        responsive : true,
        processing : true,
        serverSide : true,
        searching : false,
        bLengthChange : false,
        ajax: "{{ route('table.document-related', $model->id) }}",
        columns: [
            // {data : 'checkbox', name : 'checkbox'},
            {data : 'DT_Row_Index', name : 'id'},
            {data : 'document', name : 'document'}
        ]
    });
</script>