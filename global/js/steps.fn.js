var chart = (function() {
		var
		width=0,
		height=0,
		max = function(d) {
			var r = 0;
			for (var i in d) {
				if (d[i] > r) {
					r = d[i];
				}
			}
			return r;
		},
		drawChart = function(c, d, s, xo, mo) {
			var
			xInc = width / (xo||d.length - 1),
			yMax = mo||max(d),
			v = 0;
			
			// Draw the shaded background
			c.beginPath();
			c.lineWidth = 0;
			c.fillStyle = "rgba(" + s + ",0.25)";
			c.strokeStyle = "rgba(" + s + ",1)";
			c.moveTo(0, height);
			for (var i = 0; i < d.length; i++) {
				v = d[i] / yMax * height;
				c.lineTo(i * xInc, Math.abs(v - height));
			}
			c.lineTo((i - 1) * xInc, height);
			c.closePath();
			c.fill();
			
			// Draw the main stroke
			c.beginPath();
			c.lineWidth = 2;
			c.strokeStyle = "rgb(" + s + ")";
			for (var i = 0; i < d.length; i++) {
				v = d[i] / yMax * height;
				if (i === 0) { 
					c.moveTo(0, Math.abs(v - height));
				} else {
					c.lineTo(i * xInc, Math.abs(v - height));
				}
			}
			var
			p = $("#steps canvas").position(),
			x = (i - 1) * xInc + p.left,
			y = Math.abs(v - height) + p.top;
			$("#steps").append("<span style=\"position:absolute; left:" + x + "px; top:" + y + "px; color:rgb(" + s + ")\">" + d[d.length - 1] + "</span>");
			c.stroke();
		},
		init = function(id, source, c1, c2) {
			width = $("#" + id).width();
			height = $("#" + id).height();
			$.ajax({
				url:source,
				dataType:"json",
				success:function(data) {
					// Calculate the total and avarage number of steps taken
					var a = 0, t = 0, tc = [];
					for (var i = 0; i < data.steps.length; i++) {
						t += data.steps[i];
						tc.push(t);
					}
					var c = document.getElementById(id);
					if (typeof(c.getContext) === "undefined") {
						a = t / data.steps.length;
						$("#steps").html("<h1>Matt's Glorious Walk for Free Money</h1><strong>You're using IE, so no graph for you. I recommend <a href=\"http://chrome.google.com/\">Google Chrome</a></strong><br />" + t + " total steps walked so far.<br />An average of " + a + " steps per day over " + data.steps.length + " days.");
					} else {
						drawChart(c.getContext("2d"), data.steps, c1, 75);
						drawChart(c.getContext("2d"), tc, c2, 75, t);
					}
				}
			});
		};
		
		return { init:init };
		
	})();
(function() {
	$(document).ready(function() {
		chart.init("stepChart", "/global/data/steps.js", "176,210,53", "107,216,242");
	});
})();