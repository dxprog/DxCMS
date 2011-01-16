<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
	<xsl:output method="xml" />
	<xsl:template match="/">
		<rss version="2.0">
			<channel>
				<title>matt hackmann's blog</title>
				<link>http://dxprog.com/</link>
				<description>The musings, work and art of a web developer</description>
				<language>en-us</language>
				<xsl:apply-templates select="content_rss/item" />
			</channel>
		</rss>
	</xsl:template>
	
	<xsl:template match="item">
		<item>
			<title><xsl:value-of select="title" /></title>
			<link>http://dxprog.com/entry/<xsl:value-of select="perma" />/</link>
			<guid>http://dxprog.com/entry/<xsl:value-of select="perma" />/</guid>
			<pubDate><xsl:value-of select="rfcDate" /></pubDate>
			<xsl:if test="type = 'art' or type = 'video' or type = 'portfolio'">
				<enclosure url="http://dxprog.com/{meta/thumb}" type="image/jpeg" />
			</xsl:if>
			<description>
				<xsl:copy-of select="body/*" />
				<xsl:if test="postBreak">
					<a href="http://dxprog.com/entry/{perma}/">Click to Read Full Post</a>
				</xsl:if>
			</description>
		</item>
	</xsl:template>
	
</xsl:stylesheet>