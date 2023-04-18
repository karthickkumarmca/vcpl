var chart;
am4core.ready(function() {
  am4core.addLicense("CH200407582857149");
// Themes begin
am4core.useTheme(am4themes_material);
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
chart = am4core.create("case_report", am4charts.XYChart);

// Increase contrast by taking evey second color
chart.colors.step = 2;

// Add data
//chart.data = generateChartData();
chart.data = [];
chart.numberFormatter.numberFormat = "#.";


// Create axes
var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
dateAxis.renderer.minGridDistance = 50;

// Create series
function createAxisAndSeries(field, name, opposite, bullet, color_code) {
    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.min = 0;
    if(chart.yAxes.indexOf(valueAxis) != 0){
        valueAxis.syncWithAxis = chart.yAxes.getIndex(0);
    }

    var series = chart.series.push(new am4charts.LineSeries());
    //series.stroke = am4core.color(color_code);
    series.stroke = color_code;
    series.fill = color_code;
    series.dataFields.valueY = field;
    series.dataFields.dateX = "date";
    series.strokeWidth = 2;
    series.yAxis = valueAxis;
    series.name = name;
    series.tooltipText = "{name}: [bold]{valueY}[/]";
    //series.tooltip.background.fill = am4core.color(color_code);
    //series.tooltip.label.fill = am4core.color(color_code);
    series.tensionX = 0.8;
    series.showOnInit = true;

    //var interfaceColors = new am4core.InterfaceColorSet();

    switch(bullet) {
        case "triangle":
        var bullet = series.bullets.push(new am4charts.Bullet());
        bullet.width = 12;
        bullet.height = 12;
        bullet.horizontalCenter = "middle";
        bullet.verticalCenter = "middle";

        var triangle = bullet.createChild(am4core.Triangle);
        //triangle.stroke = interfaceColors.getFor("background");
        triangle.fill = am4core.color('#FF0000');
        triangle.stroke = am4core.color('#FF0000');
        triangle.strokeWidth = 2;
        triangle.direction = "top";
        triangle.width = 12;
        triangle.height = 12;
        break;



        case "rectangle":
        var bullet = series.bullets.push(new am4charts.Bullet());
        bullet.width = 10;
        bullet.height = 10;
        bullet.horizontalCenter = "middle";
        bullet.verticalCenter = "middle";
        var rectangle = bullet.createChild(am4core.Rectangle);
        rectangle.stroke = am4core.color('#008000');
        rectangle.fill = am4core.color('#008000');
        rectangle.strokeWidth = 2;
        rectangle.width = 10;
        rectangle.height = 10;
        break;




        default:
        var bullet = series.bullets.push(new am4charts.CircleBullet());
        bullet.circle.stroke = am4core.color('#fbb507');
        bullet.circle.fill =am4core.color('#fbb507');
        bullet.circle.strokeWidth = 2;
        break;
    }

    valueAxis.renderer.line.strokeOpacity = 1;
    valueAxis.renderer.line.strokeWidth = 2;
    valueAxis.renderer.line.stroke = series.stroke;
    valueAxis.renderer.labels.template.fill = series.stroke;
    valueAxis.renderer.opposite = opposite;
}

createAxisAndSeries("confirmed", "Confirmed", false, "circle", "#fbb507");
createAxisAndSeries("recovered", "Recovered", true, "rectangle", "#008000");
createAxisAndSeries("deaths", "Deaths", true, "triangle", "#FF0000");

// Add legend
chart.legend = new am4charts.Legend();

// Add cursor
chart.cursor = new am4charts.XYCursor();


}); // end am4core.ready()
