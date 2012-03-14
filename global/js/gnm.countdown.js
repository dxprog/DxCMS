var GNM = GNM || {};

GNM.Widget = (function() {
	var

	run = function(i, el) {
		var w = GNM.Widget[$(el).attr('type')];
		if (typeof(w) === 'function') {
			var o = new w($(el));
		}
	},

	init = function() {
		$('div[gnm="true"]').each(run);
	};

	$(document).ready(init);

	return { init:init };
	
})();

GNM.Widget.Countdown = function(e) {
	
	var
	retVal = null,
	interval = 10,
	endDate = 0,
	displayDays = true,
	displayHours = true,
	displayMinutes = true,
	displaySeconds = true,
	displayMicro = true,
	getHundredthSecond = function(t) {
		var val = Math.floor((t / 10) % 100).toString();
		return (val.length == 1) ? val = "0" + val : val;
	},
	getSecond = function(t) {
		var val = Math.floor((t / 1000) % 60).toString();
		return (val.length == 1) ? val = "0" + val : val;
	},
	getMinute = function(t) {
		var val = Math.floor((t / 60000) % 60).toString();
		return (val.length == 1) ? val = "0" + val : val;
	},
	getHour = function(t) {
		var val = Math.floor((t / 3600000) % 24).toString();
		return (val.length == 1) ? val = "0" + val : val;		
	},
	getDay = function(t) {
		var val = Math.floor(t / 86400000).toString();
		return (val.length == 1) ? val = "0" + val : val;	
	},
	renderNumber = function(v) {
		var out = "";
		for (var i = 0, max = v.length; i < max; i++) {
			var n = v.substr(i, 1);
			out += '<span class="Num' + n + '">' + n + '</span>';
		}
		return out;
	},
	callback = function() {
		var
		now = (new Date()).getTime(),
		delta = endDate - now,
		t = renderNumber(getHundredthSecond(delta)),
		s = renderNumber(getSecond(delta)),
		m = renderNumber(getMinute(delta)),
		h = renderNumber(getHour(delta)),
		d = renderNumber(getDay(delta)),
		out = displayDays ? d + '<span class="days separator"> days</span>' : '';
		out += displayHours ? h + '<span class="hours separator"> hours</span>' : '';
		out += displayMinutes ? m + '<span class="minutes separator"> minutes</span>' : '';
		out += displaySeconds ? s + '<span class="seconds separator"> seconds</span>' : '';
		out += displayMicro ? t + '<span class="micro separator"></span>' : '';
		e.html(out);
	},
	init = (function() {
		
		var date = (new Date(e.attr("date"))).getTime();
		if (typeof(date) !== "number") {
			e.hide();
		} else {
			endDate = date;
			displayDays = e.attr("days") == "false" ? false : true;
			displayHours = e.attr("hours") == "false" ? false : true;
			displayMinutes = e.attr("minutes") == "false" ? false : true;
			displaySeconds = e.attr("seconds") == "false" ? false : true;
			displayMicro = e.attr("micro") == "false" ? false : true;
			setInterval(callback, interval);
		}
		
	})();
	
	
};