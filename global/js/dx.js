/* DxApi Lib */
var dx = {};

// Does a jsonp request to the API
dx.call = function(library, method, params, callback) {

	var qs = '/api/?type=json&method=' + library + '.' + method;
	for (var i in params) {
		qs += '&' + i + '=' + params[i];
	}
	$.ajax({
		url:qs,
		dataType:'jsonp',
		success:callback
	});

};

// Inserts a flash file into the left column with proper aspect and size
dx.flash = function(options) {

	var
	ratio = options.height / options.width,
	maxWidth = $('.gallerySingle').width();
	if (options.width > maxWidth) {
		options.width = maxWidth;
		options.height = Math.round(maxWidth * ratio);
	}

	$('.gallerySingle').flash(options);

};

/* Featured item rotator */
var
featured = function () {
	var
	currentItem = 0,
	maxItems = 4,
	rotatorPause = 8,
	timerHandle = null,
	timerStart = null,
	fps = 1000 / 41,
	changeImage = function() {
		currentItem++;
		if (currentItem >= maxItems) {
			$('#featured li:not(:first)').hide();
			$('#featured li:last').show().fadeOut();
			currentItem = 0;
		} else {
			$('#featured li:eq(' + currentItem + ')').fadeIn();
		}
		timerStart = (new Date()).getTime();
	},
	doProgress = function() {
		var timeDelta = (new Date()).getTime() - timerStart;
		var offsetX = 960 - ((currentItem * 240) + Math.floor(240 * (timeDelta / (rotatorPause * 1000))));
		$('#featured').css('background-position', '-' + offsetX + 'px 100%');
		if (timeDelta > rotatorPause * 1000) {
			changeImage();
		}
	},
	init = function() {
		$('#featured').find('li:not(:first)').hide();
		timerStart = (new Date()).getTime();
		timerHandle = setInterval(doProgress, fps);
		$('#featured a')
			.mouseover(function() { clearInterval(timerHandle); timerStart = (new Date()).getTime(); doProgress(); })
			.mouseout(function() { timerHandle = setInterval(doProgress, fps); timerStart = (new Date()).getTime(); });
	};
	
	init();
	
},

/* Archive calendar */
archives = function() {

	var
	currentDate = new Date(),
	currentMonth = currentDate.getMonth(),
	currentYear = currentDate.getFullYear(),
	months = [31,28,31,30,31,30,31,31,30,31,30,31],
	monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
	dateClick = function(e) {
		var
		month = '' + (currentMonth + 1),
		url = '/archives/' + (month.length < 2 ? '0' + month : month) + '/' + currentYear + '/';
		window.location.href = url;
	},
	buildCalendar = function(date) {
		
		date = Date.parse(date);
		if (date) {
			
			date = new Date(date);
			date.setDate(1);
			
			var
			month = date.getMonth(),
			monthName = monthNames[month],
			year = date.getFullYear(),
			monthLength = month == 1 ? year % 4 == 0 ? 29 : 28 : months[month],
			dayOfWeek = date.getDay(),
			out = '<table><tbody><tr>',
			day = 0;
			
			for (var i = 0, max = date.getDay(); i < max; i++) {
				out += '<td class="noDate">&nbsp;</td>';
			}
			
			for (var i = 0; i < monthLength; i++) {
				day = i + dayOfWeek;
				if (day % 7 == 0) {
					out += '</tr>';
				}
				if (day % 0 == 0) {
					out += '<tr>';
				}
				out += '<td class="day' + (i + 1) + '">' + (i + 1) + '</td>';
			}
			
			day++;
			while (day % 7 != 0) {
				out += '<td class="noDate">&nbsp;</td>';
				day++;
			}
			
			out += '</tr></tbody></table>';
			$('#archives div').html(out);
			$('#archives h3').html(monthName + ' ' + year);
			
		}
		
	},
	displayPosts = function(data) {
		
		var minDate = (currentMonth + 1) + '/1/' + currentYear;
		buildCalendar(minDate);
		
		if (data.body.content.length > 0) {
			
			for (var i = 0, count = data.body.content.length; i < count; i++) {
				
				var
				item = data.body.content[i],
				date = new Date(item.date * 1000).getDate();
				$('td.day' + date).addClass('hasPosts');
				
			}
			
		}
		
	},
	
	controlClick = function(e) {
		var rel = $(e.target).attr('rel');
		switch (rel) {
			case 'prev':
				currentMonth--;
				break;
			case 'next':
				currentMonth++;
				break;
		}
		
		if (currentMonth < 0) {
			currentMonth = 11;
			currentYear--;
		}
		
		if (currentMonth > 11) {
			currentMonth = 0;
			currentYear++;
		}
		
		updateCalendar();
		
	},
	
	updateCalendar = function() {
		var
		month = currentMonth + 1,
		year = currentYear,
		maxDate = '',
		minDate = '';
		if (month > 12) {
			month = 1;
			year++;
		}
		minDate = month + '/1/' + year;
		if (month + 1 > 12) {
			month = 0;
			year++;
		}
		maxDate = (month + 1) + '/1/' + year;
		dx.call('content', 'getContent', {'noCount':'true', 'noTags':'true', 'maxdate':maxDate, 'mindate':minDate, 'select':'title,perma,date', 'contentType':'art,video,blog,portfolio'}, displayPosts);
	},
	
	init = function() {
		$('#archives .control').click(controlClick);
		updateCalendar();
		$('#archives').delegate('.hasPosts', 'click', dateClick);
	};
	
	init();

},

/* Comments reply and pagination */
comments = function() {
	
	var
	
	// Properties
	numComments = 0,
	commentsPerPage = 30,
	pages = 0,
	currrentPage = 0,
	
	// Event callbacks
	replyClick = function(e) {
		var user = $(e.target).parent().find('.user').text();
		$('#commentBody').val('@' + user).focus();
	},
	pageClick = function(e) {
		var page = $(e.target).attr('rel');
		if (page) {
			var start = page * commentsPerPage, end = start + commentsPerPage - 1;
			console.log(start, end);
			$('.comment').show();
			$('.comment:gt(' + end + ')').hide();
			$('.comment:lt(' + start + ')').hide();
			$('.commentPages .selected').removeClass('selected');
			$('.commentPages [rel="' + page + '"]').addClass('selected');
			currentPage = page;
		}
	},
	
	// Workers
	paginate = function() {
		numComments = $('.commentReply').length;
		if (numComments > commentsPerPage) {
			pages = Math.ceil(numComments / commentsPerPage);
			$('.comment:gt(' + (commentsPerPage - 1) + ')').hide();
			var pagination = '<ul class="commentPages">';
			for (var i = 0; i < pages; i++) {
				var selected = i == 0 ? ' selected' : '';
				pagination += '<li><a href="#comments" class="commentPage' + selected + '" rel="' + i + '">' + (i + 1) + '</a></li>';
			}
			pagination += '</ul>';
			$('#comments').append(pagination);
			$(pagination).insertAfter('#comments h3');
			$('.commentPage').click(pageClick);
		}
	},
	init = function() {
		$('.commentReply').click(replyClick);
		paginate();
	};
	
	init();
	
},

gallery = function() {

	var
	itemClick = function(e) {
		
		var href = $(e.target).attr('href');
		if (!href) {
			href = $(e.target).parents('a:first').attr('href');
		}
		
		// Extract the perma from the URL
		href = href.match(/entry\/(.*?)\//)[1];
		$('#galleryBg').fadeIn(400, function() { loadItem(href); });
		$('#galleryItem').show().animate({width:'800px'});
		
		e.preventDefault();
	},
	loadItem = function(href) {
		$('#galleryItem').prepend('<iframe src="/gallery/' + href + '/" id="galleryItem" frameborder="0"></iframe>');
	},
	closeClick = function(e) {
		$('#galleryBg').fadeOut();
		$('#galleryItem').animate({width:0}).fadeOut();
		$('#galleryItem iframe').remove();
	},
	init = function() {
		$('#gallery a').click(itemClick);
		$('#galleryBg').detach().appendTo('body').click(closeClick);
		$('#galleryItem').css('margin-top', (($(window).height() - $('#galleryItem').height()) / 2) + 'px');
		$('#galleryItem .close').click(closeClick);
	};
	
	$(init);

};

/* --- AUTO EXECUTING FUNCTIONS --- */

/* Comment form submission stuffs (starts on DOM load) */
(function() {

	var commentSubmit = function(e) {

		var error = false;

		// Run form validation
		$('label.error').removeClass('error');
		if ($('#commentName').length && !$('#commentName').val()) {
			error = true;
			$('label[for="commentName"]').addClass('error');
		}
		if ($('#commentEmail').length && !$('#commentEmail').val()) {
			error = true;
			$('label[for="commentEmail"]').addClass('error');
		} else if ($('#commentEmail').length) {
			
			// Check for valid e-mail
			email = $('#commentEmail').val();
			if (!email.match(/([\w\d]+)@([\w\d]+)\.([\w])/)) {
				error = true;
			} else {
				$('#botProof').val(hex_md5(email));
			}
			
		}
		if (!$('#commentBody').val()) {
			error = true;
			$('label[for="commentBody"]').addClass('error');
		}
		
		return !error;
	},
	
	init = function() {
		$('#commentForm').submit(commentSubmit);
	};

	$(init);
	
})();

/* Video embed wrapper */
(function($) {

	$.fn.video = function(url) {
		this.each(function() {
			$(this).append('<embed width="610" height="350" src="/global/flash/jcplayer.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" align="middle" play="true" loop="true" scale="noScale" wmode="window" devicefont="false" bgcolor="#ffffff" name="jcplayer" menu="true" allowfullscreen="true" salign="TL" allowscriptaccess="sameDomain" flashvars="videoURL=' + url + '&amp;autoHide=true&amp;margins=10&amp;offsetY=35&amp;highlightColor=0xf26716" type="application/x-shockwave-flash" />');
		});
	};

})(jQuery);

/* Search */
(function($) {
	var
	search = function(e) {
		var query = $('#search input[type="text"]').val();
		if (query.length > 0) {
			window.location = '/search/' + query + '/';
		}
		e.preventDefault();
	},
	init = function() {
		$('#search form').submit(search);
	};
	$(init);
}(jQuery));

/* Lightbox for blog image galleries */
$(function() {
	if ($('.gallery a').length > 0) {
		$('.gallery a').lightBox();
	}
});