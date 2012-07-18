<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	
	<xsl:template match="/">
		<ul class="adminNav">
			<li><a href="/admin/blog/overview/">Blog Posts</a></li>
			<li><a href="/admin/portfolio/overview/">Portfolio</a></li>
			<li><a href="/admin/art/overview/">Art</a></li>
			<li><a href="/admin/code/overview/">Code</a></li>
			<li><a href="/admin/comic/overview/">Comics</a></li>
			<li><a href="/admin/kvps/overview/">Site Options</a></li>
			<li class="user">Logged in as <strong><xsl:value-of select="//user" /></strong> ( <a href="/admin/logout/">Logout</a> )</li>
		</ul>
	</xsl:template>
	
</xsl:stylesheet>