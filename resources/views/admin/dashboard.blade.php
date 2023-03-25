@extends('layouts.main')
@section('title', 'HOMERGY | Dashboard')
@section('user', 'Admin')
@section('user_group', 'Administrator')
@section('menu')

@endsection

@section('myMenu')
	{!! $menu !!}
@endsection

@section('style')
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}
</style>
@endsection

@section('content')
		

<div class="card mb-3">
    <div class="card-header">
      <div class="row flex-between-end">
        <nav aria-label="breadcrumb">
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{URL('dashboard')}}"><i class="fas fa-home"></i></a></li>
		    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
		  </ol>
		</nav>
      </div>
    </div>
    <div class="card-body bg-light">
      <div class="tab-content">
        	<div class="form-title">
        		@if(isset($editable[13]))
						<!-- <a href="{{URL('/')}}/ads/create" style="float: right;" class="btn btn-primary btn-flat btn-pri"> -->
							<!-- <i class="fa fa-plus"></i> Add -->
						<!-- </a> -->
						@endif
				</div>
			<br>
			<br>
			<div class="form-body" style="font-size: 12px;">
				<div class="row">
					<div class="col-lg-6">
						<div class="card mb-3">
						  <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url({{URL('/')}}/assets_admin_v2/img/icons/spot-illustrations/corner-4.png);">
						  </div> <!--/.bg-holder-->
						  <div class="card-body position-relative">
						    <div class="row">
						      <div class="col-lg-12">
						      </div>
						    </div>
						  </div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="card mb-3">
						  <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url({{URL('/')}}/assets_admin_v2/img/icons/spot-illustrations/corner-4.png);">
						  </div> <!--/.bg-holder-->
						  <div class="card-body position-relative">
						    <div class="row">
						      <div class="col-lg-12">
						      </div>
						    </div>
						  </div>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="card mb-3">
						  <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url({{URL('/')}}/assets_admin_v2/img/icons/spot-illustrations/corner-4.png);">
						  </div> <!--/.bg-holder-->
						  <div class="card-body position-relative">
						    <div class="row">
						      <div class="col-lg-12">
						        <h3></h3>
						        <div id="chartdiv"></div>
						      </div>
						    </div>
						  </div>
						</div>
					</div>
				</div>
			</div>
      	</div>
    </div>
</div>

@endsection

@section('page-script')
<script type="text/javascript">
	$(function(){

		$('#dashboardSideMenu').addClass('active');
		
	});
</script>
<script>
am5.ready(function() {

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(am5xy.XYChart.new(root, {
  panX: false,
  panY: false,
  wheelX: "panX",
  wheelY: "zoomX",
  layout: root.verticalLayout
}));


// Add legend
// https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
var legend = chart.children.push(am5.Legend.new(root, {
  centerX: am5.p50,
  x: am5.p50
}));

var data = {!! json_encode($query1); !!};


// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xRenderer = am5xy.AxisRendererX.new(root, {
  cellStartLocation: 0.1,
  cellEndLocation: 0.9
});

var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
  categoryField: "year",
  renderer: xRenderer,
  tooltip: am5.Tooltip.new(root, {})
}));

xRenderer.grid.template.setAll({
  location: 1
})

xAxis.data.setAll(data);

var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  min: 0,
  renderer: am5xy.AxisRendererY.new(root, {
    strokeOpacity: 0.1
  })
}));


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
function makeSeries(name, fieldName, stacked) {
  var series = chart.series.push(am5xy.ColumnSeries.new(root, {
    stacked: stacked,
    name: name,
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: fieldName,
    categoryXField: "year"
  }));

  series.columns.template.setAll({
    tooltipText: "{name}, {categoryX} : {valueY}",
    width: am5.percent(90),
    tooltipY: am5.percent(10)
  });
  series.data.setAll(data);

  // Make stuff animate on load
  // https://www.amcharts.com/docs/v5/concepts/animations/
  series.appear();

  series.bullets.push(function() {
    return am5.Bullet.new(root, {
      locationY: 0.5,
      sprite: am5.Label.new(root, {
        text: "{valueY}",
        fill: root.interfaceColors.get("alternativeText"),
        centerY: am5.percent(50),
        centerX: am5.percent(50),
        populateText: true
      })
    });
  });

  legend.data.push(series);
}
<?php $query1 = collect($query); ?>
@foreach($query1->where('create_by', Auth::user()->id) as $result)
	makeSeries("{{$result->device_name}} | {{$result->socket_name}}", "{{$result->id}}", true);
@endforeach



// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
chart.appear(1000, 100);

}); // end am5.ready()
</script>
@endsection