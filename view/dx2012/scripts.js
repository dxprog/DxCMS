(function($) {

	var
	
	$body = $('body'),
	$window = $(window),
	
	fadeRemove = function() {
		$(this).remove();
	},
	
	lightbox = (function() {
		
		var
		
		$lightbox = null,
		$bg = null,
		
		closeArtbox = function(e) {
			$lightbox.fadeOut(400, fadeRemove);
			$bg.fadeOut(400, fadeRemove);
		},
		
		hideDescription = function(e) {
			$lightbox.find('article').fadeOut(400, fadeRemove);
		},
		
		imageLoaded = function() {
			var
			data = {
				meta:{
					file:this.getAttribute('src'),
				},
				dimensions:{
					width:this.width,
					height:this.height
				},
				body:this.getAttribute('title')
			};
			ajaxCallback(data);
		},
		
		ajaxCallback = function(data) {
			
			// Data validation
			if (data) {
				
				var
				windowWidth = $window.width(),
				windowHeight = $window.height(),
				scrollTop = $window.scrollTop(),
				maxWidth = windowWidth * .9,
				maxHeight = windowHeight * .9,
				imgWidth = data.dimensions.width,
				imgHeight = data.dimensions.height,
				description = '',
				x = 0,
				y = 0;
				
				$bg = $('<div class="artboxBg" >')
					.insertBefore($lightbox)
					.fadeTo(400, 0.5)
					.on('click', closeArtbox);
				
				// If the image is larger than the viewport, resize to aspect within the constraints
				if (imgWidth > maxWidth) {
					imgHeight = (imgHeight / imgWidth) * maxWidth;
					imgWidth = maxWidth;
				}
				
				if (imgHeight > maxHeight) {
					imgWidth = (imgWidth / imgHeight) * maxHeight;
					imgHeight = maxHeight;
				}
				
				// Calculate the position of the lightbox so that it's centered in the window
				x = (windowWidth - imgWidth) / 2;
				y = (windowHeight - imgHeight) / 2 + scrollTop;
				
				$lightbox
					.animate({
						left:x + 'px',
						top:y + 'px',
						width:imgWidth,
						height:imgHeight
					},
					400,
					function() {
						
						if (data.title || data.body) {
							description = '<article>';
							if (data.title) {
								description += '<h2>' + data.title + '</h2>';
							}
							if (data.body) {
								if (data.body.indexOf('<p>') === -1) {
									description += '<p>' + data.body + '</p>';
								} else {
									description += data.body;
								}
							}
							description += '<span class="close">Hide description</span></article>';
						}
						
						$(this)
							.removeClass('loading')
							.css('top', (y - scrollTop) + 'px')
							.addClass('displaying')
							.append('<img src="' + data.meta.file + '" />' + description)
							.on('click', 'img', closeArtbox);
					});
				
				$lightbox.on('click', '.close', hideDescription);
				
			} else {
				$lightbox.removeClass('loading').addClass('error');
			}
			
		},
		
		click = function(e) {
			
			var
			$this = $(e.currentTarget),
			position = $this.offset(),
			href = e.currentTarget.tagName !== 'IMG' ? $this.find('a').attr('href') : e.currentTarget.getAttribute('src'),
			ext = href.split('.'),
			loader = null;
			
			// If this is an image and it's wrapped in an anchor, we'll pull data off of that instead
			if ($this.parent()[0].tagName === 'A') {
				href = $this.parent().attr('href');
				ext = href.split('.');
				if ('png|gif|jpg|jpeg'.indexOf(ext[ext.length - 1]) === -1) {
					e.stopPropagation();
				}
			}
			
			e.preventDefault();
			$lightbox = $('<div class="artbox loading"></div>');
			
			// Create a duplicate looking element to overlay the one just clicked. This will be come the lightbox later
			$lightbox.css({ left:position.left + 'px', top:position.top + 'px', width:$this.width() + 'px', height:$this.height() });
			$body.append($lightbox);
			$lightbox = $('.artbox');
			
			// Get information about the full content entry
			if (href.indexOf('/gallery/') === 0) {
				console.log(e.currentTarget);
				$.ajax({
					url:href + '?target=json&id=' + e.currentTarget.getAttribute('data-id'),
					dataType:'json',
					success:ajaxCallback
				});
			} else {
				loader = document.createElement('img');
				loader.onload = imageLoaded;
				loader.src = href;
				loader.title = e.currentTarget.getAttribute('title');
			}
			
			return false;
		},
		
		init = function() {
			$body
				.on('click', '.artwork', click)
				.on('click', '.postImage', click)
				.on('click', '.gallery img', click)
				.on('click', '.post a[href$="jpg"],.post a[href$="png"],.post a[href$="gif"],.post a[href$="jpeg"]', function(e) { e.preventDefault(); return false; });
		};
		
		return { init:init };
		
	}()),
	
	gallery = (function() {
			
		var
		
		$gallery = $('#gallery'),
		columnCount = 4,
		columnWidth = $gallery.width() / columnCount,
		columns = [0, 0, 0, 0, 0],
		
		render = function() {
			
			var maxHeight = 0, i = 0;
			
			$gallery.find('.artwork').each(function() {
				
				var
				
				$this = $(this),
				width = $this.outerWidth(true),
				height = $this.outerHeight(true),
				column = 0,
				colHeight = 0;
				
				$gallery.removeClass('loading');
				
				for (i = 0; i < columnCount; i++) {
					if (columns[i] < colHeight || i == 0) {
						column = i;
						colHeight = columns[i];
					}
				}
				
				$this.css({ left:(column * columnWidth) + 'px', top:colHeight + 'px' });
				columns[column] += height;
				
			});
			
			// Make all the columns even
			for (i = 0; i < columnCount; i++) {
				maxHeight = maxHeight < columns[i] ? columns[i] : maxHeight;
			}
			
			var out = '';
			for (i = 0; i < columnCount; i++) {
				out += '<div class="spacer" style="left:' + (i * columnWidth) + 'px; top:' + columns[i] + 'px; height:' + (maxHeight - columns[i]) + 'px"></div>';
			}
			$gallery.append(out).height(maxHeight);
			
		},

	
		init = (function() {
			if ($gallery.length > 0) {
				window.onload = render;
			}
		}());
	
	}());

	posts = function() {
	
		$('.post').each(function() {
			var $this = $(this);
			$this.find('.body').css('min-height', $this.find('dl').outerHeight(true) + 'px');
		});
	
	},
	
	init = (function() {
		lightbox.init();
		posts();
	}());

}(jQuery));