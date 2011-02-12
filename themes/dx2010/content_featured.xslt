<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="/">
		<img class="segments" src="/themes/dx2010/images/featured_segments.png" />
		<ul>
			<xsl:for-each select="//content_item">
				<li>
					<img src="{meta/featured_image}" alt="{title}" />
					<a href="/entry/{perma}">
						<h2><xsl:value-of select="title" /></h2>
						<p><xsl:value-of select="meta/featured_teaser" /></p>
					</a>
				</li>
			</xsl:for-each>
		</ul>
		<script type="text/javascript">
			dx.featured();
		</script>
	</xsl:template>
	
</xsl:stylesheet>