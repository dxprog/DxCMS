<!DOCTYPE html>
<html>
	<head>
		<title>matt hackamnn - web developer</title>
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="/global/js/dx.js"></script>
		<link rel="stylesheet" type="text/css" href="/view/dx2012/1140.css" />
		<link rel="stylesheet" type="text/css" href="/view/dx2012/styles.css" />
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
		<section id="featured">
			
		</section>
		<section id="main" class="row">
			<section id="content" class="eightcol">
				{CONTENT}
			</section>
			<section id="sidebar" class="fourcol last">
				{TWITTER}
				{RELATED}
				{ARCHIVES}
			</section>
		</section>
		<footer>
			Copyright &copy; 2012 Matt Hackmann
		</footer>
		<script type="text/javascript" src="/view/dx2012/scripts.js"></script>
	</body>
</html>