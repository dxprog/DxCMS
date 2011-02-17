<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="/global/css/code_styles.css" />
		<link rel="stylesheet" type="text/css" href="/global/css/gallery_styles.css" />
		<link rel="stylesheet" type="text/css" href="/themes/dx2010/styles.css?20110211" />
		<link rel="stylesheet" type="text/css" href="/themes/dx2010/admin.css" />
		<link rel="stylesheet" type="text/css" href="/global/css/ui-lightness/jquery-ui-1.8.7.custom.css" />
		<link rel="stylesheet" type="text/css" href="/global/css/jquery.lightbox-0.5.css" />		
		<script type="text/javascript" src="/global/js/jquery-1.5.min.js"></script>
		<script type="text/javascript" src="/global/js/jquery-ui-1.8.7.custom.min.js"></script>
		<script type="text/javascript" src="/global/js/jquery.lightbox-0.5.min.js"></script>
		<script type="text/javascript" src="/global/js/jquery.swfobject.1-0-9.min.js"></script>
		<script type="text/javascript" src="/global/js/dx.js?20110211"></script>
		<!--[if IE]>
		<script>
			var e = ("abbr,article,aside,audio,canvas,datalist,details,figure,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,time,video").split(',');
			for (var i = 0; i < e.length; i++)
				document.createElement(e[i]);
		</script>
		<![endif]-->
		<title>{TITLE}</title>
	</head>
	<body>
		<header id="pageHead">
			<h1>matt hackmann - web developer - artist - nerd</h1>
			<nav>
				<ul class="nav">
					<li><a href="/">Home</a></li>
					<li><a href="/portfolio/">Portfolio</a></li>
					<li><a href="/art/">Art</a></li>
					<li><a href="/video/">Videos</a></li>
					<li><a href="/comic/">Comics</a></li>
					<li><a href="/contact/">Contact</a></li>
				</ul>
				<ul class="social">
					<li class="rss"><a href="http://feeds.feedburner.com/dxprog" title="Subscribe to my RSS feed" target="_blank">Subscribe to my RSS feed</a></li>
					<li class="twitter"><a href="http://twitter.com/dxprog" title="Follow me on Twitter" target="_blank">Follow me on Twitter</a></li>
				</ul>
			</nav>
		</header>
		<h2 id="sectionHead">Administration Panel</h2>
		<section id="content">
			<section id="body">
				{CONTENT}
			</section>
			<section id="rightColumn">
				<h2>Search</h2>
				<ul>
					<form action="/admin/search/" method="post">
						<input type="text" name="query" id="query" />
						<button type="submit">Search</button>
					</form>
				</ul>
				<h2>Site Options</h2>
				<ul>
					<li><a href="/admin/kvps/new/">Create New Option</a></li>
					<li><a href="/admin/kvps/overview/">View Current Options</a></li>
				</ul>
				<h2>Blog</h2>
				<ul>
					<li><a href="/admin/blog/new/">New Blog Entry</a></li>
					<li><a href="/admin/blog/overview/">Blog Entries</a></li>
				</ul>
				<h2>Gallery</h2>
				<ul>
					<li><a href="/admin/gallery/overview/">View Gallery Items</a></li>
					<li><a href="/admin/gallery/new/">New Gallery Item</a></li>
				</ul>
				<h2>Featured</h2>
				<ul>
					<li><a href="/admin/featured/overview/">View Featured Items</a></li>
					<li><a href="/admin/featured/new/">New Featured Item</a></li>
				</ul>
				<h2>Comics</h2>
				<ul>
					<li><a href="/admin/comic/new/">New Comic</a></li>
				</ul>
			</section>
		</section>
		<footer id="pageFooter">
			<p>Copyright &copy; Matt Hackmann 2003-2010</p>
		</footer>
	</body>
</html>