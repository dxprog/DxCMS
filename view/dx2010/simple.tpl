<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="/global/css/code_styles.css" />
		<link rel="stylesheet" type="text/css" href="/global/css/gallery_styles.css" />
		<link rel="stylesheet" type="text/css" href="/global/css/jquery.lightbox-0.5.css" />
		<link rel="stylesheet" type="text/css" href="/view/dx2010/styles.css?{CSS_DATE}" />
		<link rel="alternate" type="application/rss+xml" title="dxprog's News Feed" href="http://feeds.feedburner.com/dxprog" />
		<script type="text/javascript" src="/global/js/jquery-1.5.min.js"></script>
		<script type="text/javascript" src="/global/js/jquery.lightbox-0.5.min.js"></script>
		<script type="text/javascript" src="/global/js/jquery.swfobject.1-0-9.min.js"></script>
		<script type="text/javascript" src="/global/js/dx.js?{JS_DATE}"></script>
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
		<div id="wrapper">
			<header id="pageHead">
				<h1>matt hackmann - web developer - artist - nerd</h1>
				<nav>
					<ul class="nav">
						<li><a href="/">Home</a></li>
						<li><a href="/portfolio/">Portfolio</a></li>
						<li><a href="/art/">Art</a></li>
						<li><a href="/video/">Videos</a></li>
						<li><a href="/comic/">Comics</a></li>
						<li><a href="/about/">About</a></li>
					</ul>
					<ul class="social">
						<li class="rss"><a href="http://feeds.feedburner.com/dxprog" title="Subscribe to my RSS feed" target="_blank">Subscribe to my RSS feed</a></li>
						<li class="twitter"><a href="http://twitter.com/dxprog" title="Follow me on Twitter" target="_blank">Follow me on Twitter</a></li>
					</ul>
				</nav>
			</header>
			<h2 id="sectionHead"></h2>
			<section id="content">
				<section id="body" class="full">
					{CONTENT}
				</section>
			</section>
			<footer id="pageFooter">
				<p>Copyright &copy; Matt Hackmann 2003-2010</p>
			</footer>
		</div>
		{GA}
	</body>
</html>