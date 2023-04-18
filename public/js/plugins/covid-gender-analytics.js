var menGroupChart;
var womenGroupChart;
am4core.ready(function() {
am4core.addLicense("CH200407582857149");
// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

var menIconPath = "M53.5,476c0,14,6.833,21,20.5,21s20.5-7,20.5-21V287h21v189c0,14,6.834,21,20.5,21 c13.667,0,20.5-7,20.5-21V154h10v116c0,7.334,2.5,12.667,7.5,16s10.167,3.333,15.5,0s8-8.667,8-16V145c0-13.334-4.5-23.667-13.5-31 s-21.5-11-37.5-11h-82c-15.333,0-27.833,3.333-37.5,10s-14.5,17-14.5,31v133c0,6,2.667,10.333,8,13s10.5,2.667,15.5,0s7.5-7,7.5-13 V154h10V476 M61.5,42.5c0,11.667,4.167,21.667,12.5,30S92.333,85,104,85s21.667-4.167,30-12.5S146.5,54,146.5,42 c0-11.335-4.167-21.168-12.5-29.5C125.667,4.167,115.667,0,104,0S82.333,4.167,74,12.5S61.5,30.833,61.5,42.5z"

var womenIconPath = "M288 48c0 26.51-21.49 48-48 48s-48-21.49-48-48c0-26.509 21.49-48 48-48s48 21.491 48 48z M359.5 256l24.5-17.75-66.643-103.058c-2.96-4.49-7.979-7.192-13.357-7.192h-128c-5.378 0-10.396 2.702-13.357 7.192l-66.643 103.058 24.5 17.75 55.322-71.798 19.229 44.87-67.051 122.928h61.333l10.667 160h32v-160h16v160h32l10.667-160h61.333l-67.052-122.929 19.229-44.87 55.323 71.799z"

//men's chart
menGroupChart = am4core.create("male-group-analytics", am4charts.SlicedChart);
menGroupChart.hiddenState.properties.opacity = 0; // this makes initial fade in effect

// menGroupChart.data = [{
// 	"name": "Active",
// 	"value": 354
// }, {
// 	"name": "Recovered",
// 	"value": 245
// }, {
// 	"name": "Death",
// 	"value": 187
// }
// ];

var series = menGroupChart.series.push(new am4charts.PictorialStackedSeries());
series.dataFields.value = "value";
series.dataFields.category = "name";
series.alignLabels = true;

series.labels.template.text = "{category}: {value}";
series.slices.template.tooltipText = "{category}: {value}";
series.legendSettings.labelText = '{category}';
series.legendSettings.valueText = '{value}';

series.maskSprite.path = menIconPath;
series.ticks.template.locationX = 1;
series.ticks.template.locationY = 0.5;

series.labelsContainer.width = 200;

menGroupChart.legend = new am4charts.Legend();
menGroupChart.legend.position = "left";
menGroupChart.legend.valign = "bottom";


//women's chart
womenGroupChart = am4core.create("female-group-analytics", am4charts.SlicedChart);
womenGroupChart.hiddenState.properties.opacity = 0; // this makes initial fade in effect


var series = womenGroupChart.series.push(new am4charts.PictorialStackedSeries());
series.dataFields.value = "value";
series.dataFields.category = "name";
series.alignLabels = true;

series.labels.template.text = "{category}: {value}";
series.slices.template.tooltipText = "{category}: {value}";
series.legendSettings.labelText = '{category}';
series.legendSettings.valueText = '{value}';

series.maskSprite.path = womenIconPath;
series.ticks.template.locationX = 1;
series.ticks.template.locationY = 0.5;

series.labelsContainer.width = 200;

womenGroupChart.legend = new am4charts.Legend();
womenGroupChart.legend.position = "left";
womenGroupChart.legend.valign = "bottom";

}); // end am4core.ready()
