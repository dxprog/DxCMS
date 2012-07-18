<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
	<xsl:output method="html" />
	<xsl:template match="/">
		<div id="gallery" class="portfolio">
			<h2>Portfolio</h2>
			<xsl:for-each select="//content_item">
				<div class="sixcol">
					<xsl:if test="position() mod 2 = 0">
						<xsl:attribute name="class">sixcol last</xsl:attribute>
					</xsl:if>
					<xsl:variable name="url">
						<xsl:choose>
							<xsl:when test="meta/link"><xsl:value-of select="meta/link" /></xsl:when>
							<xsl:otherwise>/entry/<xsl:value-of select="perma" />/</xsl:otherwise>
						</xsl:choose>
					</xsl:variable>
					<xsl:variable name="date"><xsl:value-of select="date" /></xsl:variable>
					<a href="{$url}" title="{title}" target="_blank">
						<img src="/thumb.php?file={meta/thumb}&amp;height=200" alt="{title}" />
						<h3>
							<xsl:value-of select="title" />
						</h3>
					</a>
					<time datetime="{php:function('date', 'Y-d-m\Th:i:sP', $date)}">
						<xsl:value-of select="php:function('date', 'Y', $date)" />
					</time>
					<div class="description">
						<xsl:value-of select="body" disable-output-escaping="yes" />
					</div>
				</div>
			</xsl:for-each>
		</div>
	</xsl:template>
	
</xsl:stylesheet>