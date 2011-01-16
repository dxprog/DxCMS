<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="/global/css/code_styles.css" />
		<link rel="stylesheet" type="text/css" href="/global/css/gallery_styles.css" />
		<link rel="stylesheet" type="text/css" href="/themes/dx2010/styles.css?20110105" />
		<script type="text/javascript" src="/global/js/jquery-1.4.4.min.js"></script>
		<script type="text/javascript" src="/global/js/jquery.swfobject.1-0-9.min.js"></script>
		<script type="text/javascript" src="/global/js/dx.js?20110105"></script>
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
				<ul>
					<li><a href="/">Home</a></li>
					<li><a href="/portfolio/">Portfolio</a></li>
					<li><a href="/art/">Art</a></li>
					<li><a href="/video/">Videos</a></li>
					<li><a href="/comic/">Comics</a></li>
					<li><a href="/contact/">Contact</a></li>
					<li>
						<a href="http://feeds.feedburner.com/dxprog" title="Subscribe to my RSS feed" target="_blank"><img src="/themes/dx2010/images/nav_rss.png" alt="RSS Icon" /></a>
						<a href="http://twitter.com/dxprog" title="Follow me on Twitter" target="_blank"><img src="/themes/dx2010/images/nav_twitter.png" alt="RSS Icon" class="last" /></a>
					</li>
				</ul>
			</nav>
		</header>
		<h2 id="sectionHead">{SECTION}</h2>
		<section id="content">
			<section id="body" class="full">
				{CONTENT}
			</section>
		</section>
		<footer id="pageFooter">
			<p>Copyright &copy; Matt Hackmann 2003-2010</p>
		</footer>
		
		<!-- ANALYTICS -->
		<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
		try {
			// var pageTracker = _gat._getTracker("UA-280226-1");
			// pageTracker._trackPageview();
		} catch(err) {}</script>
		<!-- END ANALYTICS -->
		
	</body>
</html>