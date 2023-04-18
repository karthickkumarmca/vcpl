var agewiseAffectedPieChart;
var agewiseRecoveredPieChart;
var agewiseDeathsPieChart;
am4core.ready(function() {
am4core.addLicense("CH200407582857149");
// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

generatePieLegendChart("confirmed", "pie-legend-affected-analytics");
generatePieLegendChart("recovered", "pie-legend-recovered-analytics");
generatePieLegendChart("deaths", "pie-legend-deaths-analytics");

function generatePieLegendChart(chart_type, chart_div) {
	var pieSeries;
	if(chart_type == "confirmed") {
    	// Create chart instance
    	agewiseAffectedPieChart = am4core.create(chart_div, am4charts.PieChart);

		// Add and configure Series
		pieSeries = agewiseAffectedPieChart.series.push(new am4charts.PieSeries());
		pieSeries.dataFields.value = "value";
		pieSeries.dataFields.category = "title";

		// Let's cut a hole in our Pie chart the size of 30% the radius
		agewiseAffectedPieChart.innerRadius = am4core.percent(30);

		// Add a legend
		agewiseAffectedPieChart.legend = new am4charts.Legend();
		agewiseAffectedPieChart.legend.valueLabels.template.disabled = true;
		agewiseAffectedPieChart.paddingBottom = 20;
	} else if(chart_type == "recovered") {
		// Create chart instance
		agewiseRecoveredPieChart = am4core.create(chart_div, am4charts.PieChart);

		// Add and configure Series
		pieSeries = agewiseRecoveredPieChart.series.push(new am4charts.PieSeries());
		pieSeries.dataFields.value = "value";
		pieSeries.dataFields.category = "title";

		// Let's cut a hole in our Pie chart the size of 30% the radius
		agewiseRecoveredPieChart.innerRadius = am4core.percent(30);

		// Add a legend
		agewiseRecoveredPieChart.legend = new am4charts.Legend();
		agewiseRecoveredPieChart.legend.valueLabels.template.disabled = true;
		agewiseRecoveredPieChart.paddingBottom = 20;
	} else {
		// Create chart instance
		agewiseDeathsPieChart = am4core.create(chart_div, am4charts.PieChart);

		// Add and configure Series
		pieSeries = agewiseDeathsPieChart.series.push(new am4charts.PieSeries());
		pieSeries.dataFields.value = "value";
		pieSeries.dataFields.category = "title";

		// Let's cut a hole in our Pie chart the size of 30% the radius
		agewiseDeathsPieChart.innerRadius = am4core.percent(30);

		// Add a legend
		agewiseDeathsPieChart.legend = new am4charts.Legend();
		agewiseDeathsPieChart.legend.valueLabels.template.disabled = true;
		agewiseDeathsPieChart.paddingBottom = 20;
	}

	// Put a thick white border around each Slice
	pieSeries.slices.template.stroke = am4core.color("#fff");
	pieSeries.slices.template.strokeWidth = 2;
	pieSeries.slices.template.strokeOpacity = 1;
	pieSeries.slices.template
	// change the cursor on hover to make it apparent the object can be interacted with
	.cursorOverStyle = [
	{
		"property": "cursor",
		"value": "pointer"
	}
	];

	pieSeries.alignLabels = false;
	pieSeries.labels.template.bent = true;
	pieSeries.labels.template.radius = 3;
	pieSeries.labels.template.padding(0,0,0,0);

	pieSeries.ticks.template.disabled = true;
	pieSeries.labels.template.disabled = true;
	// Create a base filter effect (as if it's not there) for the hover to return to
	var shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter);
	shadow.opacity = 0;

	// Create hover state
	var hoverState = pieSeries.slices.template.states.getKey("hover"); // normally we have to create the hover state, in this case it already exists

	// Slightly shift the shadow and make it more prominent on hover
	var hoverShadow = hoverState.filters.push(new am4core.DropShadowFilter);
	hoverShadow.opacity = 0.7;
	hoverShadow.blur = 5;

}


}); // end am4core.ready()
