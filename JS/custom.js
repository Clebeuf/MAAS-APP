
		/* implementation heavily influenced by http://bl.ocks.org/1166403 */

		// define dimensions of graph
		var m = [80, 80, 80, 80]; // margins (t-r-b-l)
		var w = (document.getElementById("main-container").offsetWidth)*.8;;	// width
		var h = (window.innerHeight)*.5; // height
			

		// data hard coded in for now
		//var data = [["October 13, 2014 11:13:00",17,17],["October 14, 2014 11:13:00",17,12],["October 15, 2014 11:13:00",18,27],["October 16, 2014 11:13:00",17,17],["October 17, 2014 11:13:00",17,16],["October 18, 2014 11:13:00",17,12],["October 19, 2014 11:13:00",17,15],["October 20, 2014 11:13:00",17,15],["October 21, 2014 11:13:00",18,13],["October 22, 2014 11:13:00",18,13]];
		
		// start time and end time hard coded in for now
		//var startTime = new Date("October 13, 2014 11:13:00");
		//var endTime = new Date("October 23, 2014 11:13:00");
		
		// X scale
		var x = d3.time.scale().domain([startTime, endTime]).range([0, w]);
		x.tickFormat(d3.time.format("%Y-%m-%d"));


		// range (y) is hard coded in until it gets swtiched to json
		var y = d3.scale.linear().domain([min_temp, max_temp]).range([h, 0]);

		// create a line function that can convert data[] into x and y points
		var line1 = d3.svg.line()
			// assign the X function to plot our line as we wish
			.x(function(d) {
				// return the X coordinate where we want to plot this datapoint
				return x(new Date(d[0])); // use the 2n index of data
			})
			.y(function(d) {
				// return the Y coordinate where we want to plot this datapoint
				return y(d[1]);
		})
			
		var line2 = d3.svg.line()
			// assign the X function to plot our line as we wish
			.x(function(d) {
				// return the X coordinate where we want to plot this datapoint
				return x(new Date(d[0])); 
			})
			.y(function(d) {
				// return the Y coordinate where we want to plot this datapoint
				return y(d[2]); // use the 3rd index of data
		})


			// Add an SVG element with the desired dimensions and margin.
			var graph = d3.select("#graph").append("svg:svg")
				    .attr("width", w + m[1] + m[3])
				    .attr("height", h + m[0] + m[2])
				    .append("svg:g")
				    .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

			// create yAxis
			var xAxis = d3.svg.axis().scale(x).tickSize(10).tickSubdivide(100);
			// Add the x-axis.
			graph.append("svg:g")
			      .attr("class", "x axis")
			      .attr("transform", "translate(0," + h + ")")
			      .call(xAxis);


			// create left yAxis
			var yAxisLeft = d3.svg.axis().scale(y).ticks(10).orient("left");
			// Add the y-axis to the left
			graph.append("svg:g")
			      .attr("class", "y axis")
			      .attr("transform", "translate(0,0)")
			      .call(yAxisLeft);
			

  			// do this AFTER the axes above so that the line is above the tick-lines
    		graph.append("svg:path")
    			.attr("d", line1(data))
    			.attr("class", "data1")
    			.attr("transform", "translate(0,0)");
    		graph.append("svg:path")
    			.attr("d", line2(data))
    			.attr("class", "data2");

    		// add axis lables
    		graph.append("text")
			    .attr("class", "x label")
			    .attr("text-anchor", "end")
			    .attr("x", w)
			    .attr("y", h - 6)
			    .attr("dx", "1em")
			    .text("Date on Mars");

			graph.append("text")
			    .attr("class", "y label")
			    .attr("text-anchor", "end")
			    .attr("y", 6)
			    .attr("dy", "1em")
			    .attr("transform", "rotate(-90)")
			    .text("Temperature in °C");

