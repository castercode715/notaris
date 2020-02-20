@extends('base.main')
@section('title') Dashboard @endsection
@section('page_icon') <i class="fa fa-dashboard"></i> @endsection
@section('page_title') Highest Investment @endsection
@section('page_subtitle') Report @endsection

@section('content')
<div class="box box-solid">
	<div class="box-body">
		<div class="box-body">
			<form method="post" action="{{ route('dashboard.data-highest-investment') }}" id="form-highest-investment">
				@csrf
				<div class="row">
					<div class="col-md-4">
						<label for="year">Year*</label>
						<select class="form-control" name="year" id="txt-year">
							<option value="">- All -</option>
							@for($y=2018; $y<=2020; $y++)
							<option value="{{ $y }}">{{ $y }}</option>
							@endfor
						</select>
					</div>
					<div class="col-md-4">
						<label for="month">Month</label>
						<select class="form-control" name="month" id="txt-month">
							<option value="">- All -</option>
							@foreach($month as $key => $value)
							<option value="{{ $key }}">{{ $value }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-2">
						<button type="submit" class="btn btn-primary btn-refresh" style="margin-top: 25px;">
							<i class="fa fa-refresh"></i>
						</button>
					</div>
				</div>
			</form>
		</div>
		<div class="box-footer" id="asset-chart">
			<div class="row">
				<div class="col-md-4 col-md-offset-4"><h4 id="box-date"></h4></div>
			</div>
			<canvas id="myChart" height="80"></canvas>
			<div id="asset"></div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
var progress = document.getElementById('animationProgress');
var ctx = document.getElementById("myChart");
var option = {
    type: 'bar',
    data: {
    	labels : [],
    	datasets : [],
    	ids : []
    },
    options: {
    	// events : ['click'],
    	onClick : function(e){
    		var activePoints = myChart.getElementsAtEvent(e);
			if (activePoints[0]) {
				var chartData = activePoints[0]['_chart'].config.data;
				var idx = activePoints[0]['_index'];
				var label = chartData.labels[idx];
				var myId = chartData.ids[idx];
				var id = label.substring(label.lastIndexOf("(")+1, label.lastIndexOf(")"));
				console.log(myId);
				var url = "highest-investment/asset";
				$.get(url, function(data){
					$('#asset').show();
					$('#asset').html(data);

					$('#datatable').DataTable({
					    responsive : true,
					    processing : true,
					    serverSide : true,
					    ajax: "table/highest-investment/"+myId,
					    columns: [
					        {data : 'DT_Row_Index', name : 'id'},
					        {data : 'asset_name', name : 'asset_name'},
					        {data : 'amount', name : 'amount'},
					        {data : 'invest_tenor', name : 'invest_tenor'},
					        {data : 'number_interest', name : 'number_interest'},
					        {data : 'status', name : 'status'},
					    ]
					});
				});
			}
    	},
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
}
window.myChart = new Chart(ctx, option);

$('.btn-refresh').click(function(e){
	e.preventDefault();

	var form = $('#form-highest-investment'),
        url = form.attr('action'),
        method = form.attr('method');

    var setdate = $('#txt-month').val() == '' ? 'Highest Investment On : '+$('#txt-year').val() : 'Highest Investment On : '+months[ $('#txt-month').val() ]+' '+$('#txt-year').val();

    document.getElementById('box-date').innerHTML = setdate;

    $.ajax({
		url : url,
		method : method,
		data : form.serialize(),
		success : function(response){
			if(response.labels.length > 0)
			{
				var labels = response.labels;
				var datas = response.datasets;
				var ids = response.ids;

				myChart.data.labels = labels;
				myChart.data.datasets = datas;
				myChart.data.ids = ids;
				myChart.update();
			}
			else
			{
				// myChart.destroy();
				myChart.data.labels = [];
				myChart.data.datasets = [];
				myChart.data.ids = [];
				myChart.update();

				swal({
	                type : 'info',
	                title : 'Information',
	                text : 'Data not found'
	            });
			}

			$('#asset').hide();
		},
		error : function(er){
			// myChart.destroy();
			myChart.data.labels = [];
			myChart.data.datasets = [];
			myChart.data.ids = [];
			myChart.update();
			swal({
                type : 'error',
                title : 'Error',
                text : 'Failed to retrieve data'
            });
		}
	});
});

var months = new Array();
	months[0] = "";
	months[1] = "January";
	months[2] = "February";
	months[3] = "March";
	months[4] = "April";
	months[5] = "May";
	months[6] = "June";
	months[7] = "July";
	months[8] = "August";
	months[9] = "September";
	months[10] = "October";
	months[11] = "November";
	months[12] = "December";


</script>
@endpush