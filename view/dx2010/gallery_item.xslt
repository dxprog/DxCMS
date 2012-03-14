<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="xml" />
	<xsl:template match="/">
		<xsl:apply-templates select="gallery_item" />
	</xsl:template>
	
	<xsl:template match="gallery_item">
		<div id="gallerySingle">
			<img src="{//meta/file}" alt="{title}" />
		</div>
		<article>
			<header>
				<h3><xsl:value-of select="//title" /></h3>
				<div class="meta">
					<time datetime="{rfcDate}">
						<span class="day">
							<xsl:value-of select="day" />
						</span>
						<span class="month">
							<xsl:value-of select="month" />
							<span class="year">
								<xsl:value-of select="year" />
							</span>
						</span>
					</time>
					<ul class="tags">
						<xsl:apply-templates select="tags/tags_item/name" />
					</ul>
				</div>
			</header>
			<div class="body">
				<xsl:valu-of select="//body" />
			</div>
		</article>
	</xsl:template>

	<xsl:template match="name">
		<li>
			<a href="http://dxprog.com/tag/{.}/"><xsl:value-of select="." /></a>
		</li>
	</xsl:template>
	
</xsl:stylesheet>