//can get vars from php (put this in php): <script type="text/javascript">var color = "<?= $color ?>";</script>
//document.write("search_term = " + search_term); // this is js

//define margin variables
var margin = {top: 19, right: 20, bottom: 20, left: 19},
    width = 960 - margin.right - margin.left, // width
    height = 145 - margin.top - margin.bottom, // height
    cellSize = 17; // cell size

// define date formatting variables
var day = d3.time.format("%w"),
    week = d3.time.format("%U"),
    percent = d3.format(".1%"),
    format = d3.time.format("%Y-%m-%d");


// domain: min and max input values (I assume that if input > max or < min, just gets set to min)
// range: output range to specified array (d3.range(9) ==> [0, 1, 2, 3, 4, 5, 6, 7, 8])
var color = d3.scale.quantize()
   // .domain([0, 1])
    .domain([0,.75]) // changed the domain so the calendar looks more interesting
    .range(d3.range(9));

// create some objects
var svg = d3.select("#chart").selectAll("svg")
    .data(d3.range(2009, 2013))
  .enter().append("svg")
    .attr("width", width + margin.right + margin.left)
    .attr("height", height + margin.top + margin.bottom)
    .attr("class", "RdYlGn")
  .append("g")
    .attr("transform", "translate(" + (margin.left + (width - cellSize * 53) / 2) + "," + (margin.top + (height - cellSize * 7) / 2) + ")");


var data = [{i:1, name:"Jan"},
            {i:2, name:"Feb"},
		    {i:3, name:"Mar"},
		    {i:4, name:"Apr"},
		    {i:5, name:"May"},
		    {i:6, name:"Jun"},
		    {i:7, name:"Jul"},
		    {i:8, name:"Aug"},
		    {i:9, name:"Sep"},
		    {i:10, name:"Oct"},
		    {i:11, name:"Nov"},
		    {i:12, name:"Dec"}];
var x = d3.scale.linear().domain([0, data.length]).range([0, width - (cellSize * 2)]);
var canvas = d3.select("#chart").selectAll("svg");
var label_width = cellSize * 3;

canvas.selectAll("text")
	.data(data)
	.enter().append("svg:text")
		.attr("transform", "translate(" + cellSize * 2.5 + ",0)")
		.attr("x", function(datum, index) { return x(index) + label_width; })
		//.attr("y")
		.attr("dx", -10)
		.attr("dy", ".8em")
		.attr("text-anchor", "middle")
		.text(function (datum) {return datum.name;});

svg.append("text")
    .attr("transform", "translate(-6," + cellSize * 3.5 + ")rotate(-90)")
    .attr("text-anchor", "middle")
    .text(String);

// creates all days? giving them their coords on the page
var rect = svg.selectAll("rect.day")
    .data(function(d) { return d3.time.days(new Date(d, 0, 1), new Date(d + 1, 0, 1)); })
  .enter().append("rect")
    .attr("class", "day")
    .attr("width", cellSize)
    .attr("height", cellSize)
    .attr("x", function(d) { return week(d) * cellSize; })
    .attr("y", function(d) { return day(d) * cellSize; })
    .datum(format);

// add text to the mouseover text
rect.append("title")
    .text(function(d) { return d; });

svg.selectAll("path.month")
    .data(function(d) { return d3.time.months(new Date(d, 0, 1), new Date(d + 1, 0, 1)); })
  .enter().append("path")
    .attr("class", "month")
    .attr("d", monthPath);

// connect csv and svg: date is the key, percentage is value
d3.csv("cal_get_data.php", function(csv){
	var data = d3.nest()
    .key(function(d) { return d.Date; })
    .rollup(function(d) { return d[0].Percentage; }) //return (d[0].Close - d[0].Open) / d[0].Open; })
    .map(csv);

  rect.filter(function(d) { return d in data; })
     .attr("class", function(d) { return "day q" + color(data[d]) + "-9"; })
     .select("title")
     .text(function(d) { return d + ": " + percent(data[d]); });
});

function monthPath(t0) {
  var t1 = new Date(t0.getFullYear(), t0.getMonth() + 1, 0),
      d0 = +day(t0), w0 = +week(t0),
      d1 = +day(t1), w1 = +week(t1);
  return "M" + (w0 + 1) * cellSize + "," + d0 * cellSize
      + "H" + w0 * cellSize + "V" + 7 * cellSize
      + "H" + w1 * cellSize + "V" + (d1 + 1) * cellSize
      + "H" + (w1 + 1) * cellSize + "V" + 0
      + "H" + (w0 + 1) * cellSize + "Z";
}
