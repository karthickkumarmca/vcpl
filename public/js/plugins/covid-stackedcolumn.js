var stackedColumnChart;
am4core.ready(function() {
am4core.addLicense("CH200407582857149");
// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
stackedColumnChart = am4core.create("stacked-column-analytics", am4charts.XYChart3D);

// Create axes
var categoryAxis = stackedColumnChart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "date";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 30;

var valueAxis = stackedColumnChart.yAxes.push(new am4charts.ValueAxis());
valueAxis.title.text = "Number Of Cases";
valueAxis.renderer.labels.template.adapter.add("text", function(text) {
	return text;
});

var series3 = stackedColumnChart.series.push(new am4charts.ColumnSeries3D());
series3.dataFields.valueY = "deaths";
series3.dataFields.categoryX = "date";
series3.name = "Deaths";
series3.clustered = false;
series3.columns.template.tooltipText = "Deaths: [bold]{valueY}[/]";
series3.fill = am4core.color('#FF0000');

var series2 = stackedColumnChart.series.push(new am4charts.ColumnSeries3D());
series2.dataFields.valueY = "recovered";
series2.dataFields.categoryX = "date";
series2.name = "Recovered";
series2.clustered = false;
series2.columns.template.tooltipText = "Recovered: [bold]{valueY}[/]";
series2.fill = am4core.color('#008000');

// Create series
var series = stackedColumnChart.series.push(new am4charts.ColumnSeries3D());
series.dataFields.valueY = "confirmed";
series.dataFields.categoryX = "date";
series.name = "Cofirmed";
series.clustered = false;
series.columns.template.tooltipText = "Cofirmed: [bold]{valueY}[/]";
series.columns.template.fillOpacity = 10;
series.fill = am4core.color('#fbb507');



stackedColumnChart.legend = new am4charts.Legend();
stackedColumnChart.legend.reverseOrder = true;
}); // end am4core.ready()
