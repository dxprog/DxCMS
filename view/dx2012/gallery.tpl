<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="initial-scale=1.0, width=device-width, height=device-height, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<title>{TITLE}</title>
		<link rel="stylesheet" type="text/css" href="/view/dx2012/all.styles.css?date={CSS_DATE}" />
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
			<section id="content" class="twelvecol">
				{CONTENT}
			</section>
		</section>
		<footer>
			Copyright &copy; 2012 Matt Hackmann
		</footer>
		<script type="text/javascript" src="/view/dx2012/all.scripts.js?date={JS_DATE}"></script>
		{GA}
	</body>
</html>