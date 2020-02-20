<div class="box-body row">
	<div class="col-md-8">
		<div class="info-box bg-aqua">
			<span class="info-box-icon"><i class="fa fa-money"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">total investment</span>
				<span class="info-box-number">{{ "Rp " . number_format($totalInvestment,0,',','.') }}</span>

				<div class="progress">
				<div class="progress-bar" style="width: {{ $remainingPrecentage  }}%"></div>
				</div>
				<span class="progress-description">
				Remaining : {{ "Rp " . number_format($remaining,0,',','.') }}, Max : {{ "Rp " . number_format($model->price_loan,0,',','.') }}
				</span>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="info-box bg-aqua">
			<span class="info-box-icon"><i class="fa fa-users"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">investor</span>
				<span class="info-box-number">
					<h3 style="margin: 5px;font-weight: bold;">{{ $totalInvestor }}</h3>
				</span>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-users"></i> Investor</h3>
			</div>
			<div class="box-body">
				<table id="datatable-investor" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Name</th>
							<th>Amount</th>
							<th>Tenor</th>
							<th>Interest(%)</th>
							<th>Daily Int.</th>
							<th>Total Int.</th>
							<th width="50px">Status</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>