<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">
		<section id="comic">
			<xsl:apply-templates select="comic" />
		</section>
	</xsl:template>
	
	<xsl:template match="comic">
		<h3><xsl:value-of select="title" /></h3>
		<div class="comic_nav">
			<xsl:if test="string-length(previous/perma) &gt; 0">
				<a href="/comic/l4m0r-the-hedgehog/" class="prev">First</a>
				<a href="/comic/{previous/perma}/" class="prev">Previous</a>
			</xsl:if>
			<xsl:if test="string-length(next/perma) &gt; 0">
				<a href="/comic/" class="next">Latest</a>
				<a href="/comic/{next/perma}/" class="next">Next</a>
			</xsl:if>
		</div>
		<img src="/comics/{image}" alt="{title}" />
		<div class="comic_nav bottom">
			<xsl:if test="string-length(previous/perma) &gt; 0">
				<a href="/comic/l4m0r-the-hedgehog/" class="prev">First</a>
				<a href="/comic/{previous/perma}/" class="prev">Previous</a>
			</xsl:if>
			<xsl:value-of select="date" />
			<xsl:if test="string-length(next/perma) &gt; 0">
				<a href="/comic/" class="next">Latest</a>
				<a href="/comic/{next/perma}/" class="next">Next</a>
			</xsl:if>
		</div>
	</xsl:template>
	
</xsl:stylesheet>