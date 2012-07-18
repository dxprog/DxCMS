<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">

	<xsl:template match="/">
		<xsl:variable name="date"><xsl:value-of select="sidebar_twitter/created_at" /></xsl:variable>
		<aside class="twitter">
			<h3>Latest Tweet</h3>
			<p><xsl:value-of select="sidebar_twitter/text" disable-output-escaping="yes" /></p>
			<time datetime="{php:function('date', 'Y-d-m\Th:i:sP', $date)}"><xsl:value-of select="sidebar_twitter/created_at_relative" /> ago</time>
			<a href="http://twitter.com/#!/dxprog" target="_blank" class="follow">Follow Me <xsl:text disable-output-escaping="yes">&amp;raquo;</xsl:text></a>
		</aside>
	</xsl:template>
	
</xsl:stylesheet>