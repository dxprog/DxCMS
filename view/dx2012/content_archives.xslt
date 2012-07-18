<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
	<xsl:template match="/">
		<aside id="content_archives">
			<h3>Archive</h3>
			<ul>
				<xsl:for-each select="content_archives/content_archives_item">
					<li>
						<xsl:variable name="date"><xsl:value-of select="timestamp" /></xsl:variable>
						<a href="/archives/{php:function('date', 'm', $date)}/{php:function('date', 'Y', $date)}/" title="{count} posts">
							<xsl:value-of select="text" />
						</a>
					</li>
				</xsl:for-each>
			</ul>
		</aside>
	</xsl:template>
	
</xsl:stylesheet>