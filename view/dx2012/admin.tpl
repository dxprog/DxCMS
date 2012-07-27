<!DOCTYPE html>
<html>
	<head>
		<title>matt hackamnn - web developer</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="initial-scale=1.0, width=device-width, height=device-height, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="/global/js/dx.js"></script>
		<script type="text/javascript" src="/global/js/jquery-ui-1.8.7.custom.min.js"></script>
		<link rel="stylesheet" type="text/css" href="/view/dx2012/1140.css" />
		<link rel="stylesheet" type="text/css" href="/view/dx2012/styles.css" />
		<link rel="stylesheet" type="text/css" href="/view/dx2012/admin.css" />
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
			{ADMIN_NAV}
			<section id="content" class="twelvecol" class="admin">
				{CONTENT}
			</section>
		</section>
		<footer>
			Copyright &copy; 2012 Matt Hackmann
		</footer>
	</body>
</html>