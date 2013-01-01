<!DOCTYPE html>
<html>
	<head>
		<title>{TITLE}</title>
		<link rel="stylesheet" type="text/css" href="/view/dx2012/all.styles.css?date={CSS_DATE}" />
		<link rel="alternate" type="application/rss+xml" title="dxprog's News Feed" href="http://feeds.feedburner.com/dxprog" />
		<!--[if IE]>
		<script>
			var e = ("abbr,article,aside,audio,canvas,datalist,details,figure,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,time,video").split(',');
			for (var i = 0; i < e.length; i++)
				document.createElement(e[i]);
		</script>
		<![endif]-->
	</head>
	<body>
		<header>
			<h1>Matt Hackmann</h1>
			{NAVIGATION}
			<div class="search">
				<form action="/search/" method="get">
					<input type="text" name="q" id="q" />
					<button type="submit">Search</button>
				</form>
			</div>
		</header>
		<section id="main" class="row">
			{ADMIN_NAV}
			<section id="content" class="twelvecol" class="admin">
				{CONTENT}
			</section>
		</section>
		<footer>
			Copyright &copy; 2012 Matt Hackmann
		</footer>
		<script type="text/javascript" src="/view/dx2012/all.scripts.js?date={JS_DATE}"></script>
		<script type="text/javascript" src="/global/js/admin/admin.js"></script>
	</body>
</html>