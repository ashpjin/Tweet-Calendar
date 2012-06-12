//can get vars from php (put this in php): <script type="text/javascript">var color = "<?= $color ?>";</script>
//document.write("search_term = " + search_term); // this is js

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

//define margin variables
var margin = {top: 19, right: 20, bottom: 20, left: 19},
    width = 960 - margin.right - margin.left, // width
    height = 136 - margin.top - margin.bottom, // height
    cellSize = 17; // cell size

// create some objects
var svg = d3.select("#chart").selectAll("svg")
    .data(d3.range(2009, 2013))
  .enter().append("svg")
    //.attr("width", width + margin.right + margin.left)
    .attr("width", 960)
	.attr("height", 136)
	//.attr("height", height + margin.top + margin.bottom)
   .append("g")		// offsets all shapes
   // .attr("transform", "translate(" + (margin.left + (width - cellSize * 53) / 2) + "," + (margin.top + (height - cellSize * 7) / 2) + ")")
	.attr("transform", "translate(" + 29 + "," + 8 + ")")
;

var x = d3.scale.linear().domain([0, data.length]).range([0, width]);
var canvas = d3.select("#chart").selectAll("svg");
var label_width = 50;
canvas.selectAll("text")
	.data(data)
	.enter().append("svg:text")
		.attr("x", function(datum, index) { return x(index) + label_width; })
		//.attr("y")
		.attr("dx", 25)
		.attr("dy", "1.2em")
		.attr("text-anchor", "middle")
		.text(function (datum) {return datum.name;});

// creates vertical labels for years
svg.append("text")
	//.attr("transform", "translate(-6," + cellSize * 3.5 + ")rotate(-90)")
    .attr("transform", "translate(-6," + 59.5 + ")rotate(-90)")
    .attr("text-anchor", "middle")
    .text(String);

