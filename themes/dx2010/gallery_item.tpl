<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="/global/css/code_styles.css" />
		<link rel="stylesheet" type="text/css" href="/global/css/gallery_styles.css" />
		<link rel="stylesheet" type="text/css" href="/global/css/jquery.lightbox-0.5.css" />
		<link rel="stylesheet" type="text/css" href="/themes/dx2010/styles.css?20110105" />
		<style type="text/css">
			body { width:750px; }
			#content, #body { width:750px !important; }
		</style>
		<link rel="alternate" type="application/rss+xml" title="dxprog's News Feed" href="http://feeds.feedburner.com/dxprog" />
		<script type="text/javascript" src="/global/js/jquery-1.5.min.js"></script>
		<script type="text/javascript" src="/global/js/jquery.lightbox-0.5.min.js"></script>
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
		{GA}
	</body>
</html>