<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="/global/css/code_styles.css" />
		<link rel="stylesheet" type="text/css" href="/global/css/gallery_styles.css" />
		<link rel="stylesheet" type="text/css" href="/global/css/jquery.lightbox-0.5.css" />
		<link rel="stylesheet" type="text/css" href="/themes/dx2010/styles.css?20110204" />
		<link rel="alternate" type="application/rss+xml" title="dxprog's News Feed" href="http://feeds.feedburner.com/dxprog" />
		<script type="text/javascript" src="/global/js/jquery-1.4.4.min.js"></script>
		<script type="text/javascript" src="/global/js/jquery.lightbox-0.5.min.js"></script>
		<script type="text/javascript" src="/global/js/jquery.swfobject.1-0-9.min.js"></script>
		<script type="text/javascript" src="/global/js/dx.js?20110204"></script>
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
		<h2 id="sectionHead">Blog</h2>
		<section id="content">
			<section id="body">
				{CONTENT}
			</section>
			<section id="rightColumn">
				{TWITTER}
				<aside id="search">
					<h2>Search</h2>
					<form>
						<input type="text" />
						<button>Go</button>
					</form>
				</aside>
				<div id="ad">
					{AD}
				</div>
				{MUSIC}
				{POPULAR}
				<aside id="archives">
					<h2>Archives</h2>
					<a href="javascript:void(0);" class="control" rel="prev">Previous</a>
					<h3></h3>
					<a href="javascript:void(0);" class="control" rel="next">Next</a>
					<div>
						<script type="text/javascript">
							archives();
						</script>
					</div>
				</aside>
				{TAGCLOUD}
			</section>
		</section>
		<footer id="pageFooter">
			<p>Copyright &copy; Matt Hackmann 2003-2010</p>
		</footer>
		{GA}
	</body>
</html>