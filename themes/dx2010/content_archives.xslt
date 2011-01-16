<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">
		<xsl:apply-templates select="content_archives/archives" />
	</xsl:template>
	
	<xsl:template match="archives">
		<section id="content_archives">
			<header>
				<h1>Archives</h1>
			</header>
			<ul>
				<xsl:for-each select="archive">
					<li>
						<a href="{url}" title="View posts from {title}">
							<xsl:value-of select="title" />
						</a>
					</li>
				</xsl:for-each>
			</ul>
		</section>
	</xsl:template>
	
</xsl:stylesheet>