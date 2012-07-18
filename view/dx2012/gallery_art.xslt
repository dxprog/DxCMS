<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" />
	<xsl:template match="/">
		<ul id="gallery" class="art loading">
			<xsl:for-each select="//content_item">
				<li class="artwork" data-id="{id}">
					<a href="/gallery/{perma}/">
						<img src="/thumb.php?file={meta/file}&amp;width=300" alt="{title}" />
						<h3><xsl:value-of select="title" /></h3>
					</a>
				</li>
			</xsl:for-each>
		</ul>
	</xsl:template>
	
</xsl:stylesheet>