	<div class="box box-solid">
		<!-- Box Language -->
		<div class="box-body">
			<!-- start accordion -->
			<div class="box-group" id="accordion">
				<div class="panel box box-solid">
					<div class="box-header with-border">
						<h4 class="box-title"><strong>
							<a href="#collapse1" data-toggle="collapse" data-parent="#accordion">Indonesia</a>
						</strong></h4>
						<div class="box-tools pull-right">
							<a href="{{ route('asset.edit-new', [base64_encode($model->asset_lang_id), 'IND']) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
						</div>
					</div>
					<div id="collapse1" class="panel-collapse collapse in">
						<div class="box-body">
							<h3><strong>{{ $model->asset_name }}</strong></h3>
							{!! html_entity_decode($model->description) !!}
							@if($model->file_resume != null)
							<a href="/files/asset/{{ $model->file_resume }}" target="_blank" class="btn btn-xs btn-primary"><i class="fa fa-download"></i> Download Resume File</a>
							@endif
							@if($model->file_fiducia != null)
							&nbsp;|&nbsp;
							<a href="/files/asset/{{ $model->file_fiducia }}" target="_blank" class="btn btn-xs btn-primary"><i class="fa fa-download"></i> Download Fiducia File</a>
							@endif
						</div>
					</div>
				</div>
				@php $no = 2; @endphp
				@foreach($language as $l)
					@php 
					$get = $asset->getAssetBasedOnLanguage($model->id, $l->code);
					@endphp
				<div class="panel box box-solid">
					<div class="box-header with-border">
						<h4 class="box-title"><strong>
							<a href="#collapse{{ $no }}" data-toggle="collapse" data-parent="#accordion">{!! ucwords(strtolower($l->language)) !!}</a>
						</strong></h4>
						@if($get)
						<div class="box-tools pull-right">
							<a href="{{ route('asset.edit-new', [base64_encode($get->id), $l->code]) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
						</div>
						@endif
					</div>
					<div id="collapse{{ $no }}" class="panel-collapse collapse">
						<div class="box-body">
							@if($get)
								<h3><strong>{{ $get->asset_name }}</strong></h3>
								{!! $get->description !!}
								@if($get->file_resume != null)
								<a href="/files/asset/{{ $get->file_resume }}" target="_blank" class="btn btn-xs btn-primary"><i class="fa fa-download"></i> Download Resume File</a>
								@endif
								@if($get->file_fiducia != null)
								&nbsp;|&nbsp;<a href="/files/asset/{{ $get->file_fiducia }}" target="_blank" class="btn btn-xs btn-primary"><i class="fa fa-download"></i> Download Fiducia File</a>
								@endif
							@else
								<p align="center">Please add description using this language</p>
								<p align="center"><a href="{{ route('asset.create-new',[ base64_encode($model->id), $l->code]) }}" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> Add Description</a></p>
							@endif
						</div>
					</div>
				</div>
					@php $no++; @endphp
				@endforeach
			</div>
			<!-- end accordion -->
		</div>
		<!-- end Box Language -->
		<!-- Detail Asset -->
		<div class="box-footer">
			<div class="row">
				<div class="col-md-6">
					<!-- DETAIL -->
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-list"></i> Detail</h3>
						</div>
						<div class="box-body">
							<table class="table">
								<tbody>
									<tr>
										<th width="30%">Status</th>
										<td>
											@if($model->active == 1)
												<span class="badge bg-blue">ACTIVE</span>
											@elseif ($model->active == 0)
												<span class="badge bg-red">INACTIVE</span>
											@elseif ($model->active == 2)
												<span class="badge bg-red">CANCELED</span>
											@endif
										</td>
									</tr>
									<tr>
										<th width="30%">Class</th>
										<td>{{ $model->class }}</td>
									</tr>
									<tr>
										<th width="30%">Category</th>
										<td>{{ $model->category }}</td>
									</tr>
									<tr>
										<th width="30%">Terms &amp; Conds</th>
										<td>{{ $model->terms_conds }}</td>
									</tr>
									@if ($model->active == 2)
										<tr>
											<th width="30%">Canceled Reason</th>
											<td>{{ $model->canceled_reason }}</td>
										</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
					<!-- END - DETAIL -->
					<!-- PRICE -->
					<div class="box box-danger">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-dollar"></i> Price</h3>
						</div>
						<div class="box-body no-padding">
							<table class="table">
								<tbody>
									<tr>
										<th width="40%">Price Market</th>
										<td>{{ "Rp " . number_format($model->price_market,0,',','.') }}</td>
									</tr>
									<tr>
										<th>Price Liquidation</th>
										<td>{{ "Rp " . number_format($model->price_liquidation,0,',','.') }}</td>
									</tr>
									<tr>
										<th>Price Selling</th>
										<td>{{ "Rp " . number_format($model->price_selling,0,',','.') }}</td>
									</tr>
									<tr>
										<th>Price Loan</th>
										<td>{{ "Rp " . number_format($model->price_loan,0,',','.') }}</td>
									</tr>
									<tr>
										<th>Credit Tenor</th>
										<td>{{ $model->credit_tenor.' Days ('.date('d/m/Y', strtotime($model->date_available)).' - '.date('d/m/Y', strtotime($model->date_expired)).')' }}</td>
									</tr>
									<tr>
										<th>Interest</th>
										<td>{{ $model->interest.'%' }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- END - PRICE -->
				</div>
				<div class="col-md-6">
					<!-- OWNER -->
					<div class="box box-warning">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-user"></i> Owner</h3>
						</div>
						<div class="box-body no-padding">
							<table class="table">
								<tbody>
									<tr>
										<th width="50%">Owner Name</th>
										<td>{{ $model->owner_name }}</td>
									</tr>
									<tr>
										<th>Identity Number</th>
										<td>{{ $model->owner_ktp_number }}</td>
									</tr>
									<tr>
										<th>Family Card Number</th>
										<td>{{ $model->owner_kk_number }}</td>
									</tr>
									<tr>
										<th>Taxpayer Registration Number</th>
										<td>{{ $model->owner_npwp_number }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- END - OWNER -->
					<!-- DATA -->
					<div class="box box-success">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-folder"></i> Data</h3>
						</div>
						<div class="box-body no-padding">
							@if(!empty($attributes))
							<table class="table">
								<tbody>
									@foreach($attributes as $attribute)
									<tr>
										<th width="50%">{{ $attribute->description }}</th>
										<td>{{ $attribute->value }}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
							@endif
						</div>
					</div>
					<!-- END - DATA -->
					<!-- ADDRESS -->
					<div class="box box-default">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-map-marker"></i> Location</h3>
						</div>
						<div class="box-body no-padding">
							<table class="table">
								<tbody>
									<tr>
										<th width="30%">Country</th>
										<td>{{ $country }}</td>
									</tr>
									<tr>
										<th width="30%">Province</th>
										<td>{{ $province }}</td>
									</tr>
									<tr>
										<th width="30%">Regency</th>
										<td>{{ $regency }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- END - ADDRESS -->
				</div>
			</div>
		</div>
		<!-- end Detail Asset -->
		<!-- Photo -->
		<div class="box-footer">
			<div class="row">
				<!-- featured photo -->
				<div class="col-md-6">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-photo"></i> Featured</h3>
						</div>
						<div class="box-body no-padding">
							@if(!empty($featured))
							<img src="/images/asset/{{ $featured->photo }}" class="img-responsive">
							@endif
						</div>
					</div>
				</div>
				<!-- end featured photo -->
				<!-- photos -->
				<div class="col-md-6 row">
					<div class="box box-danger">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-photo"></i> Other</h3>
						</div>
						<div class="box-body row">
							@if(!empty($images))
							@foreach($images as $image)
							<div class="col-sm-4">
								<img src="/images/asset/{{ $image->photo }}" class="img-thumbnail">
							</div>
							@endforeach
							@endif
						</div>
					</div>
				</div>
				<!-- end photos -->
			</div>
			<div class="row">
				<!-- hot banner -->
				<div class="col-md-6">
					<div class="box box-warning">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-photo"></i> Hot Banner</h3>
						</div>
						<div class="box-body no-padding">
							@if(!empty($hotbanners))
							<img src="/images/asset/{{ $hotbanners->photo }}" class="img-responsive">
							@endif
						</div>
					</div>
				</div>
				<!-- end hot banner -->
				<!-- new banner -->
				<div class="col-md-6">
					<div class="box box-success">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-photo"></i> New Banner</h3>
						</div>
						<div class="box-body no-padding">
							@if(!empty($newbanners))
							<img src="/images/asset/{{ $newbanners->photo }}" class="img-responsive">
							@endif
						</div>
					</div>
				</div>
				<!-- end new banner -->
			</div>
		</div>
		<!-- end Photo -->
		<!-- created -->
		<div class="box-footer">
			<div class="box box-default">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-clock-o"></i> History</h3>
				</div>
				<div class="box-body no-padding">
					<table class="table">
						<tbody>
							<tr>
								<th>Created by</th>
								<td>{{ $uti->getUser($model->created_by) }}</td>
								<th>Updated By</th>
								<td>{{ $uti->getUser($model->updated_by) }}</td>
							</tr>
							<tr>
								<th>Created Date</th>
								<td>{{ date('d-m-Y H:i:s', strtotime($model->created_at)) }}</td>
								<th>Updated Date</th>
								<td>{{ date('d-m-Y H:i:s', strtotime($model->updated_at)) }}</td>
							</tr>
							<tr>
								<th>Takeout by</th>
								<td>{{ $uti->getUser($model->canceled_by) }}</td>
								<th>Closed By</th>
								<td>{{ $uti->getUser($model->closed_by) }}</td>
							</tr>
							<tr>
								<th>Takeout Date</th>
								<td>{{ $model->canceled_at }}</td>
								<th>Closed Date</th>
								<td>{{ $model->closed_at }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- end created -->
	</div>