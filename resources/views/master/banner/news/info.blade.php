<table class="table table-condensed table-striped">
					<tbody>
						<tr>
							<th>Title</th>
							<td>{{ $model->title }}</td>
						</tr>
						<tr>
							<th>Sub Title</th>
							<td>{{ $model->sub_title }}</td>
						</tr>
						<tr>
							<th>Description</th>
							<td>{!! html_entity_decode($model->desc) !!}</td>
						</tr>
						<tr>
							<th>Foto</th>
							<td><img src='/images/news/{{ $model->image }}' width='100px' /></td>
						</tr>
						<tr>
							<th>Iframe</th>
							<td>{{ $model->iframe }}</td>
						</tr>
						<tr>
							<th>Aktif</th>
							<td>{{ $model->active == 1 ? 'Ya' : 'Tidak' }}</td>
						</tr>
						<tr>
							<th>Created By</th>
							<td>{{ $uti->getUser($model->created_by) }}</td>
						</tr>
						<tr>
							<th>Created Date</th>
							<td>{{ date('d-m-Y H:i:s', strtotime($model->created_at)) }}</td>
						</tr>
						<tr>
							<th>Updated By</th>
							<td>{{ $uti->getUser($model->updated_by) }}</td>
						</tr>
						<tr>
							<th>Updated Date</th>
							<td>{{ date('d-m-Y H:i:s', strtotime($model->updated_at)) }}</td>
						</tr>
						
					</tbody>
				</table>