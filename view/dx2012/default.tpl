<!DOCTYPE html>
<html>
	<head>
		<title>matt hackamnn - web developer</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="initial-scale=1.0, width=device-width, height=device-height, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<script type="text/javascript" src="/global/js/jquery.lib.js"></script>
		<script type="text/javascript" src="/global/js/dx.js?{JS_DATE}"></script>
		<link rel="stylesheet" type="text/css" href="/view/dx2012/1140.css" />
		<link rel="stylesheet" type="text/css" href="/view/dx2012/styles.css?{CSS_DATE}" />
		<link rel="stylesheet" type="text/css" href="/global/css/code_styles.css" />
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
			<section id="content" class="eightcol">
				{CONTENT}
			</section>
			<section id="sidebar" class="fourcol last">
				{TWITTER}
				{POPULARART}
				{POPULAR}
				{ARCHIVES}
			</section>
		</section>
		<footer>
			Copyright &copy; 2012 Matt Hackmann
		</footer>
		<script type="text/javascript" src="/view/dx2012/scripts.js?{JS_DATE}"></script>
		{GA}
	</body>
</html>