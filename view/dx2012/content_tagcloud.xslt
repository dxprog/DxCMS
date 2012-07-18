<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">

	<xsl:template match="/">
		<aside id="tagCloud">
			<h2>Tag Cloud</h2>
			<xsl:variable name="max">
				<xsl:call-template name="max" />
			</xsl:variable>
			<div>
				<xsl:for-each select="//content_tagcloud_item">
					<xsl:variable name="size">
						<xsl:value-of select="round((count div $max + .5) * 100)" />
					</xsl:variable>
					<a title="{count}">
						<xsl:attribute name="style">font-size:<xsl:value-of select="$size" />%;</xsl:attribute>
						<xsl:attribute name="href"><xsl:value-of select="concat('/tag/', php:function('urlencode', string(name)), '/')" /></xsl:attribute>
						<xsl:value-of select="name" />
					</a>
					<xsl:if test="position() &lt; 25">, </xsl:if>
				</xsl:for-each>
			</div>
		</aside>
	</xsl:template>
	
	<xsl:template name="max">
		<xsl:for-each select="//content_tagcloud_item">
			<xsl:sort select="count" data-type="number" order="descending" />
			<xsl:if test="position() = 1">
				<xsl:value-of select="count" />
			</xsl:if>
		</xsl:for-each>
	</xsl:template>
	
</xsl:stylesheet>