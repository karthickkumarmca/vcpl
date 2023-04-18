var mapCaseChart = 1;
var setupStores;
am4core.ready(function() {
    am4core.addLicense("CH200407582857149");
	// Themes begin
	am4core.useTheme(am4themes_animated);
	// Themes end
	mapCaseChart = 2;
	// Create map instance
	mapCaseChart = am4core.create("map_drilldown_container", am4maps.MapChart);
	mapCaseChart.maxZoomLevel = 64;

	// Set map definition
	mapCaseChart.geodata = am4geodata_jamaicaLow;

	// Set projection
	mapCaseChart.projection = new am4maps.projections.Projection();

	mapCaseChart.seriesContainer.draggable = false;
	mapCaseChart.seriesContainer.resizable = false;

	mapCaseChart.maxZoomLevel = 1;

	// Add button
	var zoomOut = mapCaseChart.tooltipContainer.createChild(
		am4core.ZoomOutButton
		);
	zoomOut.align = "right";
	zoomOut.valign = "top";
	zoomOut.margin(20, 20, 20, 20);
	zoomOut.events.on("hit", function() {
		if (currentSeries) {
			currentSeries.hide();
		}
		mapCaseChart.goHome();
		zoomOut.hide();
		currentSeries = regionalSeries.US.series;
		currentSeries.show();
	});
	zoomOut.hide();

	// Create map polygon series
	var polygonSeries = mapCaseChart.series.push(new am4maps.MapPolygonSeries());
	polygonSeries.useGeodata = true;
	polygonSeries.calculateVisualCenter = true;

	// Configure series
	var polygonTemplate = polygonSeries.mapPolygons.template;
	polygonTemplate.tooltipText = "{name}";
	polygonTemplate.fill = mapCaseChart.colors.getIndex(0);

	// Load data when map polygons are ready
	mapCaseChart.events.on("ready", loadStores);

  	// Loads store data
  	function loadStores() {
  		//console.log("test");
  		//setupStores();
  	}

  	// Creates a series
  	function createSeries(heatfield) {
  		var series = mapCaseChart.series.push(new am4maps.MapImageSeries());
  		series.dataFields.value = heatfield;

  		var template = series.mapImages.template;
  		template.verticalCenter = "middle";
  		template.horizontalCenter = "middle";
  		template.propertyFields.latitude = "lat";
  		template.propertyFields.longitude = "long";
  		template.tooltipText =
  		"{name}:\n[bold]{active} Confirmed[/]\n[bold]{recovered} Recovered[/]\n[bold]{deaths} Deaths[/]";

  		var circle = template.createChild(am4core.Circle);
  		circle.radius = 10;
  		circle.fillOpacity = 0.7;
  		circle.verticalCenter = "middle";
  		circle.horizontalCenter = "middle";
  		circle.nonScaling = true;

  		var label = template.createChild(am4core.Label);
  		label.text = "{active}";
  		label.fill = am4core.color("#fff");
  		label.verticalCenter = "middle";
  		label.horizontalCenter = "middle";
  		label.nonScaling = true;

  		var heat = series.heatRules.push({
  			target: circle,
  			property: "radius",
  			min: 10,
  			max: 20
  		});

    	// Set up drill-down
    	series.mapImages.template.events.on("hit", function(ev) {

      		// Determine what we've clicked on
      		var data = ev.target.dataItem.dataContext;

      		// No id? Individual store - nothing to drill down to further
      		if (!data.target) {
      			return;
      		}

      		// Create actual series if it hasn't been yet created
      		if (!regionalSeries[data.target].series) {
      			regionalSeries[data.target].series = createSeries("count");
      			regionalSeries[data.target].series.data = data.markerData;
      		}

      		// Hide current series
      		if (currentSeries) {
      			//currentSeries.hide();
      		}

      		// Control zoom
      		if (data.type == "state") {
      			var statePolygon = polygonSeries.getPolygonById("US-" + data.state);
      			//mapCaseChart.zoomToMapObject(statePolygon);
      		}
      		//zoomOut.show();

      		// Show new targert series
      		currentSeries = regionalSeries[data.target].series;
      		currentSeries.show();
      	});
    	return series;
    }

    var regionalSeries = {};
    var currentSeries;

    setupStores = function(parish_stats) {

    // Init country-level series
    regionalSeries.US = {
    	markerData: [],
    	series: createSeries("active")
    };

    // Set current series
    currentSeries = regionalSeries.US.series;
    var stateIds = {
    	1: 'JM-09',
    	2: 'JM-11',
    	3: 'JM-08',
    	4: 'JM-07',
    	5: 'JM-10',
    	6: 'JM-13',
    	7: 'JM-12',
    	8: 'JM-06',
    	9: 'JM-14',
    	10: 'JM-05',
    	12: 'JM-04',
    	13: 'JM-02',
    	14: 'JM-03'
    }

    for(i in parish_stats) {
    	var state_id = stateIds[parish_stats[i]['id']];
    	if(state_id) {
    		var statePolygon = polygonSeries.getPolygonById(state_id);
    		if(statePolygon) {
    			regionalSeries[state_id] = {
    				target: state_id,
    				type: "state",
    				name: statePolygon.dataItem.dataContext.name,
    				active: parseInt(parish_stats[i]['total_cases']),
    				deaths: parseInt(parish_stats[i]['number_of_deaths']),
    				recovered : parseInt(parish_stats[i]['number_of_people_recovered']),
    				lat: statePolygon.visualLatitude,
    				long: statePolygon.visualLongitude,
    				state: state_id,
    				markerData: []
    			};
    		}
    		regionalSeries.US.markerData.push(regionalSeries[state_id]);
    	}

    }
    regionalSeries.US.series.data = regionalSeries.US.markerData;
  };
}); // end am4core.ready()
