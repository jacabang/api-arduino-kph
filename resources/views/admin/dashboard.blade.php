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
		
<div class="row mb-3">
  <div class="col">
    <div class="card bg-100 shadow-none border">
      <div class="row gx-0 flex-between-center">
        <div class="col-sm-auto d-flex align-items-center"><img class="ms-n2" src="{{URL('/')}}/assets_admin_v2/img/illustrations/crm-bar-chart.png" alt="" width="90" />
          <div>
            <h6 class="text-primary fs--1 mb-0">Welcome </h6>
            
            <h4 class="text-primary fw-bold mb-0">{{Auth::user()->fullname}} 
            @if(Auth::user()->username != "")<span class="text-info fw-medium">({{Auth::user()->username}})</span>@endif</h4>
          </div><img class="ms-n4 d-md-none d-lg-block" src="../assets/img/illustrations/crm-line-chart.png" alt="" width="150" />
        </div>
        <!-- <div class="col-md-auto p-3">
          <form class="row align-items-center g-3">
            <div class="col-auto">
              <h6 class="text-700 mb-0">Showing Data For: </h6>
            </div>
            <div class="col-md-auto position-relative">
              <input class="form-control form-control-sm datetimepicker ps-4" id="CRMDateRange" type="text" data-options="{&quot;mode&quot;:&quot;range&quot;,&quot;dateFormat&quot;:&quot;M d&quot;,&quot;disableMobile&quot;:true , &quot;defaultDate&quot;: [&quot;Sep 12&quot;, &quot;Sep 19&quot;] }" /><span class="fas fa-calendar-alt text-primary position-absolute top-50 translate-middle-y ms-2"> </span>
            </div>
          </form>
        </div> -->
      </div>
    </div>
  </div>
</div>
<div class="col-xxl-4">
  <div class="card h-100">
    <div class="card-header d-flex flex-between-center border-bottom py-2">
      <h6 class="mb-0"></h6><a class="btn btn-link btn-sm px-0 shadow-none" href="{{URL('records')}}">View Details<span class="fas fa-chevron-right ms-1 fs--2"></span></a>
    </div>
    <div class="card-body">
      <div id="chartdiv"></div>
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
@foreach($query1 as $result)
	makeSeries("{{$result->device_name}} | {{$result->socket_name}}", "{{$result->id}}", true);
@endforeach



// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
chart.appear(1000, 100);

}); // end am5.ready()
</script>
@endsection