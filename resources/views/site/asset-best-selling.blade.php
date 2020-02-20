@extends('base.main')
@section('title') Dashboard @endsection
@section('page_icon') <i class="fa fa-dashboard"></i> @endsection
@section('page_title') Best Selling Asset @endsection
@section('page_subtitle') Report @endsection

@section('content')
<div class="box box-solid">
	<div class="box-body">
		<div class="box-body">
			<form method="post" action="{{ route('site.data-asset-best-selling') }}" id="form-best-selling-asset">
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
			{{-- <div class="row" style="margin-left: 34px;">
				<div style="width: 10%; float: left;">
					<button class="btn btn-primary btn-sm btn-block"><i class="fa fa-search"></i></button>
				</div>
			</div> --}}
			{{-- <progress id="animationProgress" max="1" value="0" style="width: 100%"></progress> --}}
			<div id="investor"></div>
		</div>
	</div>
	{{-- <div class="overlay" style="display: none;">
		<i class="fa fa-refresh fa-spin"></i>
	</div> --}}
</div>
@endsection

@push('scripts')
<script>
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
				console.log(id);
				console.log(myId);
				var url = "asset-best-selling/investor/"+myId;
				$.get(url, function(data){
					$('#investor').show();
					$('#investor').html(data);

					$('#datatable').DataTable({
					    responsive : true,
					    processing : true,
					    serverSide : true,
					    ajax: "table/asset-best-selling/"+myId,
					    columns: [
					        {data : 'DT_Row_Index', name : 'full_name'},
					        {data : 'full_name', name : 'full_name'},
					        {data : 'amount', name : 'amount'},
					        {data : 'invest_tenor', name : 'invest_tenor'},
					        {data : 'number_interest', name : 'number_interest'},
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

/*$("#myChart").click(function(e){
	var activePoints = myChart.getElementsAtEvent(e);
	if (activePoints[0]) {
		var chartData = activePoints[0]['_chart'].config.data;
		var idx = activePoints[0]['_index'];

		var label = chartData.labels[idx];
		alert(label);
	}
});*/

$('.btn-refresh').click(function(e){
	e.preventDefault();

	var form = $('#form-best-selling-asset'),
        url = form.attr('action'),
        method = form.attr('method');

    var setdate = $('#txt-month').val() == '' ? 'Best selling asset on : '+$('#txt-year').val() : 'Best selling asset on : '+months[ $('#txt-month').val() ]+' '+$('#txt-year').val();

    document.getElementById('box-date').innerHTML = setdate;

    var investor = $('#investor');
    
    $.ajax({
		url : url,
		method : method,
		data : form.serialize(),
		success : function(response){
			// var myChart;
			if(response.labels.length > 0)
			{
				var labels = response.labels;
				var datas = response.datasets;
				var ids = response.ids;

				// delete myChart.data.labels;
				// delete myChart.data.datasets;
				
				myChart.data.labels = labels;
				myChart.data.datasets = datas;
				myChart.data.ids = ids;
				myChart.update();
				// myChart.data.labels.push(label);
				// addData(myChart, labels, datas);
				// if(myChart != null)
				// 	alert('sadsad');
				

				// var option = {
				//     type: 'bar',
				//     data: response,
				//     options: {
				//         scales: {
				//             yAxes: [{
				//                 ticks: {
				//                     beginAtZero:true
				//                 }
				//             }]
				//         }
				//     }
				// }

				// var data = {
				// 	labels : ['A','B','C'],
				// 	datasets : [{
				// 		fillColor : "rgba(99,123,133,0.4)",
			 //            strokeColor : "rgba(220,220,220,1)",
			 //            pointColor : "rgba(220,220,220,1)",
			 //            pointStrokeColor : "#fff",
			 //            data : [65,54,30]
				// 	}]
				// };
				
				// myChart.destroy();
				// var myChart = new Chart(ctx, option);
				// var myChart = new Chart(ctx).Bar(data);
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

			$('#investor').hide();
		},
		error : function(er){
			// myChart.destroy();
			myChart.data.labels = [];
			myChart.data.datasets = [];
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

function addData(chart, label, data) {
    chart.data.labels.push(label);
    chart.data.datasets.forEach((dataset) => {
        dataset.data.push(data);
    });
    chart.update();
}

function removeData(chart) {
    chart.data.labels.pop();
    chart.data.datasets.forEach((dataset) => {
        dataset.data.pop();
    });
    chart.update();
}



/*$("#myChart").click( 
   function(evt){
   		var ctx = document.getElementById("myChart").getContext("2d");
   		// from the endPoint we get the end of the bars area
   		var base = window.myChart.scale.endPoint;
   		var height = window.myChart.chart.height;
   		var width = window.myChart.chart.width;
   		// only call if event is under the xAxis
   		if(evt.pageY > base){
   			// how many xLabels we have
   			var count = window.myChart.scale.valuesCount;
   			var padding_left = window.myChart.scale.xScalePaddingLeft;
   			var padding_right = window.myChart.scale.xScalePaddingRight;
   			// calculate width for each label
   			var xwidth = (width-padding_left-padding_right)/count;
   			// determine what label were clicked on AND PUT IT INTO bar_index 
   			var bar_index = (evt.offsetX - padding_left) / xwidth;
   			// don't call for padding areas
   			if(bar_index > 0 & bar_index < count){
   				bar_index = parseInt(bar_index);
				// either get label from barChartData
				console.log("barChartData:" + barChartData.labels[bar_index]);
				// or from current data
				var ret = [];
				for (var i = 0; i < window.myChart.datasets[0].bars.length; i++) {
					ret.push(window.myChart.datasets[0].bars[i].label)
				};
				console.log("current data:" + ret[bar_index]);
				// based on the label you can call any function
    			$( "#hello" ).html(ret[bar_index] + ": " + window.myChart.datasets[0].bars[bar_index].value);
   			}
   		}
    }
);*/
</script>


{{-- <script>
	var areaChartData = {
	  	labels  : [],
	  	datasets: [
			{
				label               : 'Null',
				fillColor           : 'rgba(210, 214, 222, 1)',
				strokeColor         : 'rgba(210, 214, 222, 1)',
				pointColor          : 'rgba(210, 214, 222, 1)',
				pointStrokeColor    : '#c1c7d1',
				pointHighlightFill  : '#fff',
				pointHighlightStroke: 'rgba(220,220,220,1)',
				data                : []
			},
		]
	}

	var datanull = {
		labels : [],
		datasets : []
	}

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

	// var barChartCanvas 	= $('#barChart').get(0).getContext('2d')
	// var barChart 		= new Chart(barChartCanvas)
	var barChartOptions = {
	  //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
	  scaleBeginAtZero        : true,
	  //Boolean - Whether grid lines are shown across the chart
	  scaleShowGridLines      : true,
	  //String - Colour of the grid lines
	  scaleGridLineColor      : 'rgba(0,0,0,.05)',
	  //Number - Width of the grid lines
	  scaleGridLineWidth      : 1,
	  //Boolean - Whether to show horizontal lines (except X axis)
	  scaleShowHorizontalLines: true,
	  //Boolean - Whether to show vertical lines (except Y axis)
	  scaleShowVerticalLines  : true,
	  //Boolean - If there is a stroke on each bar
	  barShowStroke           : true,
	  //Number - Pixel width of the bar stroke
	  barStrokeWidth          : 2,
	  //Number - Spacing between each of the X value sets
	  barValueSpacing         : 5,
	  //Number - Spacing between data sets within X values
	  barDatasetSpacing       : 1,
	  //String - A legend template
	  legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
	  //Boolean - whether to make the chart responsive
	  responsive              : true,
	  maintainAspectRatio     : true
	}
	barChartOptions.datasetFill = false
	// barChart.Bar(datanull, barChartOptions);

	$('.btn-refresh').click(function(e){
		e.preventDefault();

		var form = $('#form-best-selling-asset'),
	        url = form.attr('action'),
	        method = form.attr('method');

		$.ajax({
			url : url,
			method : method,
			data : form.serialize(),
			success : function(response){
				
				var barChartCanvas 	= $('#barChart').get(0).getContext('2d')
				var barChart 		= new Chart(barChartCanvas)

				if(response.labels.length > 0)
				{
					var setdate = $('#txt-month').val() == '' ? 'Best selling asset on : '+$('#txt-year').val() : 'Best selling asset on : '+months[ $('#txt-month').val() ]+' '+$('#txt-year').val();

					document.getElementById('box-date').innerHTML = setdate;
					barChart.Bar(response, barChartOptions);
				}
				else
				{
					swal({
		                type : 'info',
		                title : 'Information',
		                text : 'Data not found'
		            });
				}
			},
			error : function(er){
				swal({
	                type : 'error',
	                title : 'Error',
	                text : 'Failed to retrieve data'
	            });
			}
		});
	});
</script> --}}
{{-- <script type="text/javascript">
	//Better to construct options first and then pass it as a parameter
	var options = {
		animationEnabled: true,
		theme: "light2",
		title:{
			text: "Cost Of Pancake Ingredients"
		},
		axisY2:{
			prefix: "$",
			lineThickness: 0				
		},
		toolTip: {
			shared: true
		},
		legend:{
			verticalAlign: "top",
			horizontalAlign: "center"
		},
		data: [
			{     
				type: "stackedBar",
				showInLegend: true,
				name: "Butter (500gms)",
				axisYType: "secondary",
				color: "#7E8F74",
				dataPoints: [
					{ y: 3, label: "India" },
					{ y: 5, label: "US" },
					{ y: 3, label: "Germany" },
					{ y: 6, label: "Brazil" },
					{ y: 7, label: "China" },
					{ y: 5, label: "Australia" },
					{ y: 5, label: "France" },
					{ y: 7, label: "Italy" },
					{ y: 9, label: "Singapore" },
					{ y: 8, label: "Switzerland" },
					{ y: 24, label: "Japan" }
				]
			},
			/*{
				type: "stackedBar",
				showInLegend: true,
				name: "Flour (1kg)",
				axisYType: "secondary",
				color: "#F0D6A7",
				dataPoints: [
					{ y: .5, label: "India" },
					{ y: 1.5, label: "US" },
					{ y: 1, label: "Germany" },
					{ y: 2, label: "Brazil" },
					{ y: 2, label: "China" },
					{ y: 2.5, label: "Australia" },
					{ y: 1.5, label: "France" },
					{ y: 1, label: "Italy" },
					{ y: 2, label: "Singapore" },
					{ y: 2, label: "Switzerland" },
					{ y: 3, label: "Japan" }
				]
			},
			{
				type: "stackedBar",
				showInLegend: true,
				name: "Milk (2l)",
				axisYType: "secondary",
				color: "#EBB88A",
				dataPoints: [
					{ y: 2, label: "India" },
					{ y: 3, label: "US" },
					{ y: 3, label: "Germany" },
					{ y: 3, label: "Brazil" },
					{ y: 4, label: "China" },
					{ y: 3, label: "Australia" },
					{ y: 4.5, label: "France" },
					{ y: 4.5, label: "Italy" },
					{ y: 6, label: "Singapore" },
					{ y: 3, label: "Switzerland" },
					{ y: 6, label: "Japan" }
					]
			},
			{
				type: "stackedBar",
				showInLegend: true,
				name: "Eggs (20)",
				axisYType: "secondary",
				color:"#DB9079",
				indexLabel: "$#total",
				dataPoints: [
					{ y: 2, label: "India" },
					{ y: 3, label: "US" },
					{ y: 6, label: "Germany"},
					{ y: 4, label: "Brazil" },
					{ y: 4, label: "China" },
					{ y: 8, label: "Australia" },
					{ y: 8, label: "France" },
					{ y: 8, label: "Italy" },
					{ y: 4, label: "Singapore" },
					{ y: 11, label: "Switzerland" },
					{ y: 6, label: "Japan" }
				]
			}*/
		]
	};

	$("#chartContainer").CanvasJSChart(options);

	$('.btn-refresh').click(function(e){
		e.preventDefault();

		var form = $('#form-best-selling-asset'),
	        url = form.attr('action'),
	        method = form.attr('method');

	    $.ajax({
			url : url,
			method : method,
			data : form.serialize(),
			success : function(response){
				if(response.labels.length > 0)
				{
					var setdate = $('#txt-month').val() == '' ? 'Best selling asset on : '+$('#txt-year').val() : 'Best selling asset on : '+months[ $('#txt-month').val() ]+' '+$('#txt-year').val();
				}
				else
				{
					swal({
		                type : 'info',
		                title : 'Information',
		                text : 'Data not found'
		            });
				}
			},
			error : function(er){
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
</script> --}}
@endpush





{{-- 
select
	asset.asset_name,
    count(asset.asset_name) as total
from
	mst_asset as asset,
    trc_transaction_detail_asset as dtl_asset,
    trc_transaction_asset as trc,
    trc_asset_investor as asset_inv
where
	asset.id = dtl_asset.asset_id
and dtl_asset.trc_asset_id = trc.id
and asset_inv.trc_detail_asset_id = dtl_asset.id
and lower(trc.status) = 'paid'
group by
	asset.asset_name
order by
	count(asset.asset_name) desc
limit 10;

 --}}