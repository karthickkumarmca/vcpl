var stackBarChart;
am4core.ready(function() {
am4core.addLicense("CH200407582857149");
// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
stackBarChart = am4core.create("stackbar-analytics", am4charts.XYChart);


// Create axes
var categoryAxis = stackBarChart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "date";
// categoryAxis.title.text = "Local country offices";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 20;
categoryAxis.renderer.cellStartLocation = 0.1;
categoryAxis.renderer.cellEndLocation = 0.9;

var  valueAxis = stackBarChart.yAxes.push(new am4charts.ValueAxis());
valueAxis.min = 0;
valueAxis.title.text = "Number Of Cases";

// Create series
function createSeries(field, name, stacked, color_code) {
	var series = stackBarChart.series.push(new am4charts.ColumnSeries());
	series.dataFields.valueY = field;
	series.dataFields.categoryX = "date";
	series.name = name;
	series.columns.template.tooltipText = "{name}: [bold]{valueY}[/]";
	series.stacked = stacked;
	series.columns.template.width = am4core.percent(95);
	series.fill = am4core.color(color_code);
}


createSeries("confirmed", "Confirmed", true, "#fbb507");
createSeries("recovered", "Recovered", true, "#008000");
createSeries("deaths", "Deaths", true, "#FF0000");

// Add legend
stackBarChart.legend = new am4charts.Legend();

}); // end am4core.ready()
