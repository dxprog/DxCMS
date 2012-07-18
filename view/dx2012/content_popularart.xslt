<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="xml" />
	<xsl:template match="/">
		<aside class="popArt">
			<h3>Popular Art</h3>
			<ul>
				<xsl:for-each select="//content_popularart_item">
					<li class="artwork" data-id="{id}">
						<xsl:if test="position() mod 2 = 0">
							<xsl:attribute name="class">artwork last</xsl:attribute>
						</xsl:if>
						<a href="/gallery/{perma}/" title="{title}">
							<img src="{meta/thumb}" alt="{title}" />
						</a>
					</li>
				</xsl:for-each>
			</ul>
			<a href="/art/" class="more">See More <xsl:text disable-output-escaping="yes">&amp;raquo;</xsl:text></a>
		</aside>
	</xsl:template>
</xsl:stylesheet>