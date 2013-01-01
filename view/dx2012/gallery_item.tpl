<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="/view/dx2012/all.styles.css?date={CSS_DATE}" />
		<style type="text/css">
			body { width:750px; }
			#content, #body { width:750px !important; }
		</style>
		<link rel="alternate" type="application/rss+xml" title="dxprog's News Feed" href="http://feeds.feedburner.com/dxprog" />
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
			<section id="content">
				<section id="body" class="full">
					{CONTENT}
				</section>
				<script type="text/javascript">
					(function() {
						$('#content a').attr('target', '_blank');
					})();
				</script>
			</section>
		</div>
		{GA}
	</body>
</html>