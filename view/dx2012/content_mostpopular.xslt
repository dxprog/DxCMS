<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="xml" />
	<xsl:template match="/">
		<aside class="popBlog">
			<h3>What's Popular</h3>
			<ol>
				<xsl:for-each select="//content_mostpopular_item">
					<li>
						<a href="/entry/{perma}/">
							<xsl:value-of select="title" />
						</a>
					</li>
				</xsl:for-each>
			</ol>
		</aside>
	</xsl:template>
</xsl:stylesheet>