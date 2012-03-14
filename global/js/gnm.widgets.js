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

GNM.Widget.news_block = function(e) {

	var
	truncate = function(t,l) {
		var cont = true, c = "";
		if (t.length <= l) { return t; }
		var c = t.substr(0, t.indexOf(" ", l));
		if (c.length === 0) { return t; }
		if (c.charAt(c.length - 1) != ".") { c += "..."; }
		return c;
	},
	options = {
		el:null,
		url:"",
		title:"",
		pages:{
			pagination:false,
			numPages:0,
			itemsPerPage:0,
			currentPage:0
		}
	},
	data = null,
	pageEvent = function(e) {
		switch ($(e.target).attr("class")) {
			case "prev":
				if (options.pages.currentPage > 0) { options.pages.currentPage--; }
				break;
			case "next":
				if (options.pages.currentPage < options.pages.numPages - 1) { options.pages.currentPage++; }
				break;
		}
		gotoPage(options.pages.currentPage);
	},
	gotoPage = function(p) {
		
		options.pages.currentPage = p;
		
		var
		b = options.pages.pagination ? options.pages.currentPage * options.pages.itemsPerPage : 0,
		o = '<h3>' + options.title + '</h3><ul class="okbNewsBox">';
		
		for (var i = b; i < options.pages.itemsPerPage + b; i++) {
			if (typeof(data[i]) === "object") {
				o += '<li><img src="' + data[i].imgSrc + '" alt="' + data[i].imgAlt + '" /><a href="' + data[i].link + '">' + data[i].title + '</a><p>' + truncate(data[i].body, 75) + '</p></li>';
			}
		}
		
		o += '</ul>';
		
		if (options.pages.pagination) {
			var pc = "", nc = "";
			if (options.pages.currentPage === 0) { pc = " disabled"; }
			if (options.pages.currentPage + 1 >= options.pages.numPages) { nc = " disabled"; }
			o += '<div class="pagination"><a href="javascript:void(0);" class="prev' + pc + '">&#x25C2;</a><strong>' + (options.pages.currentPage + 1) + '</strong> of ' + options.pages.numPages + '<a href="javascript:void(0);" class="next' + nc + '">&#x25B8;</a></div>';
		}
		
		options.el.html(o);
		options.el.find(".pagination a").not(".disabled").click(pageEvent);
		
	},
	success = function(d) {
		data = d;
		options.pages.numPages = Math.ceil(data.length / options.pages.itemsPerPage);
		gotoPage(0);
	},
	init = (function() {
	
		options.el = e;
		options.pages.itemsPerPage = parseInt(e.attr("items_per_page"));
		options.url = e.attr("src");
		options.title = e.attr("title");
		options.pages.pagination = (e.attr("pagination") == "true") ? true : false;
		options.el.addClass("gnmNewsBlock");
		
		if (typeof(options.url) !== "string") { return; }

		$.ajax({
			url:options.url,
			dataType:"jsonp",
			success:success
		});
		
		
	})();

};