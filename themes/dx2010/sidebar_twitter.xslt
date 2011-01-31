<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="/">
		<aside id="twitter">
			<h2>From Tweet Land</h2>
			<xsl:apply-templates select="sidebar_twitter" />
		</aside>
	</xsl:template>
	
	<xsl:template match="sidebar_twitter">
		<img src="{user/profile_image_url}" alt="{user/screen_name}" />
		<p>
			<xsl:value-of select="text" disable-output-escaping="yes" />
		</p>
		<time datetime="2010-01-01T00:00:00">Tweeted <xsl:value-of select="created_at" /> ago</time>
	</xsl:template>
	
</xsl:stylesheet>