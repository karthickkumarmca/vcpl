var mapImageSeries;
am4core.ready(function() {
	am4core.addLicense("CH200407582857149");

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create map instance
var worldMapChart = am4core.create("worldmap-country-analytics", am4maps.MapChart);

// Set map definition
worldMapChart.geodata = am4geodata_worldLow;

// Set projection
worldMapChart.projection = new am4maps.projections.Miller();

var polygonSeries = worldMapChart.series.push(new am4maps.MapPolygonSeries());
polygonSeries.exclude = ["AQ"];
polygonSeries.useGeodata = true;
polygonSeries.nonScalingStroke = true;
polygonSeries.strokeWidth = 0.5;
polygonSeries.calculateVisualCenter = true;

var polygonTemplate = polygonSeries.mapPolygons.template;
//polygonTemplate.tooltipText = "[bold]{name}[/]\n[bold]Total Applicants : {number_of_applicants}[/]\n[bold]Conditionally Approved  : {number_of_conditional_approved}[/]\n[bold]Approved  : {number_of_approved}[/]";
polygonTemplate.tooltipText = "[bold]{name}[/]";
polygonTemplate.fill = worldMapChart.colors.getIndex(0);
polygonTemplate.nonScalingStroke = true;

mapImageSeries = worldMapChart.series.push(new am4maps.MapImageSeries());
//imageSeries.data = mapData;
mapImageSeries.dataFields.value = "value";

var imageTemplate = mapImageSeries.mapImages.template;
imageTemplate.nonScaling = true

var circle = imageTemplate.createChild(am4core.Circle);
circle.fillOpacity = 0.7;
circle.propertyFields.fill = "color";
circle.tooltipText = "[bold]{name}[/]\n[bold]Total Applicants : {value}[/]\n[bold]Conditionally Approved  : {number_of_conditional_approved}[/]\n[bold]Approved  : {number_of_approved}[/]";

mapImageSeries.heatRules.push({
	"target": circle,
	"property": "radius",
	"min": 4,
	"max": 15,
	"dataField": "value"
})

imageTemplate.adapter.add("latitude", function(latitude, target) {
	var polygon = polygonSeries.getPolygonById(target.dataItem.dataContext.id);
	if(polygon){
		return polygon.visualLatitude;
	}
	return latitude;
})

imageTemplate.adapter.add("longitude", function(longitude, target) {
	var polygon = polygonSeries.getPolygonById(target.dataItem.dataContext.id);
	if(polygon){
		return polygon.visualLongitude;
	}
	return longitude;
})


}); // end am4core.ready()
