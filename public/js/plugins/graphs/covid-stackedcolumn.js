var stackedColumnChart;
var stackedColumnChart2;
var categoryAxis;
am4core.ready(function() {
	am4core.addLicense("CH200407582857149");
// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
stackedColumnChart = am4core.create("barchart-country-analytics", am4charts.XYChart3D);

// Create axes
categoryAxis = stackedColumnChart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "country";
categoryAxis.renderer.grid.template.location = 0.0001;
categoryAxis.renderer.cellStartLocation = 0.8;
categoryAxis.renderer.minGridDistance = 15;
categoryAxis.renderer.labels.template.rotation = 90;
categoryAxis.renderer.labels.template.verticalCenter = "middle";
categoryAxis.renderer.labels.template.horizontalCenter = "left";


var valueAxis = stackedColumnChart.yAxes.push(new am4charts.ValueAxis());
valueAxis.renderer.labels.template.adapter.add("text", function(text) {
	return text;
});

var series3 = stackedColumnChart.series.push(new am4charts.ColumnSeries3D());
series3.dataFields.valueY = "number_of_approved";
series3.dataFields.categoryX = "country";
series3.name = "Approved";
series3.clustered = false;
series3.columns.template.tooltipText = "Approved: [bold]{valueY}[/]";
series3.fill = am4core.color('#FF0000');

var series2 = stackedColumnChart.series.push(new am4charts.ColumnSeries3D());
series2.dataFields.valueY = "number_of_conditional_approved";
series2.dataFields.categoryX = "country";
series2.name = "Conditionally Approved";
series2.clustered = false;
series2.columns.template.tooltipText = "Conditionally Approved: [bold]{valueY}[/]";
series2.fill = am4core.color('#008000');

// Create series
var series = stackedColumnChart.series.push(new am4charts.ColumnSeries3D());
series.dataFields.valueY = "number_of_applicants";
series.dataFields.categoryX = "country";
series.name = "Total Applicants";
series.clustered = false;
series.columns.template.tooltipText = "No Of Applicants: [bold]{valueY}[/]";
series.columns.template.fillOpacity = 10;
series.fill = am4core.color('#fbb507');


stackedColumnChart.legend = new am4charts.Legend();
stackedColumnChart.legend.reverseOrder = true;

stackedColumnChart.scrollbarY = new am4core.Scrollbar();
stackedColumnChart.scrollbarY.parent = stackedColumnChart.leftAxesContainer;
stackedColumnChart.scrollbarY.toBack();

stackedColumnChart.scrollbarX = new am4charts.XYChartScrollbar();
stackedColumnChart.scrollbarX.parent = stackedColumnChart.bottomAxesContainer;

categoryAxis.start = 0.4699;
categoryAxis.keepSelection = true;

// Create chart instance for city analytics
stackedColumnChart2 = am4core.create("barchart-city-analytics", am4charts.XYChart3D);

// Create axes
var categoryAxis2 = stackedColumnChart2.xAxes.push(new am4charts.CategoryAxis());
categoryAxis2.dataFields.category = "city";
categoryAxis2.renderer.grid.template.location = 0;
categoryAxis2.renderer.minGridDistance = 15;
categoryAxis2.renderer.labels.template.rotation = 90;
categoryAxis2.renderer.labels.template.verticalCenter = "middle";
categoryAxis2.renderer.labels.template.horizontalCenter = "left";

var valueAxis = stackedColumnChart2.yAxes.push(new am4charts.ValueAxis());
valueAxis.renderer.labels.template.adapter.add("text", function(text) {
	return text;
});

var city_series3 = stackedColumnChart2.series.push(new am4charts.ColumnSeries3D());
city_series3.dataFields.valueY = "number_of_approved";
city_series3.dataFields.categoryX = "city";
city_series3.name = "Approved";
city_series3.clustered = false;
city_series3.columns.template.tooltipText = "Approved: [bold]{valueY}[/]";
city_series3.fill = am4core.color('#FF0000');

var city_series2 = stackedColumnChart2.series.push(new am4charts.ColumnSeries3D());
city_series2.dataFields.valueY = "number_of_conditional_approved";
city_series2.dataFields.categoryX = "city";
city_series2.name = "Conditionally Approved";
city_series2.clustered = false;
city_series2.columns.template.tooltipText = "Conditionally Approved: [bold]{valueY}[/]";
city_series2.fill = am4core.color('#008000');

// Create series
var city_series = stackedColumnChart2.series.push(new am4charts.ColumnSeries3D());
city_series.dataFields.valueY = "number_of_applicants";
city_series.dataFields.categoryX = "city";
city_series.name = "Total Applicants";
city_series.clustered = false;
city_series.columns.template.tooltipText = "No Of Applicants: [bold]{valueY}[/]";
city_series.columns.template.fillOpacity = 10;
city_series.fill = am4core.color('#fbb507');

stackedColumnChart2.legend = new am4charts.Legend();
stackedColumnChart2.legend.reverseOrder = true;

stackedColumnChart2.scrollbarY = new am4core.Scrollbar();
stackedColumnChart2.scrollbarY.parent = stackedColumnChart2.leftAxesContainer;
stackedColumnChart2.scrollbarY.toBack();

stackedColumnChart2.scrollbarX = new am4charts.XYChartScrollbar();
stackedColumnChart2.scrollbarX.parent = stackedColumnChart2.bottomAxesContainer;

categoryAxis2.start = 0.4699;
categoryAxis2.keepSelection = true;
}); // end am4core.ready()
