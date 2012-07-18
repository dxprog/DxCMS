<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
	<xsl:template match="/">
		<section id="comic">
			<xsl:apply-templates select="comic" />
		</section>
	</xsl:template>
	
	<xsl:template match="comic">
		<h2><xsl:value-of select="title" /></h2>
		<div class="comic_nav">
			<xsl:if test="string-length(previous/perma) &gt; 0">
				<a href="/comic/l4m0r-the-hedgehog/" class="first"><xsl:text disable-output-escaping="yes">&amp;laquo; </xsl:text>First</a>
				<a href="/comic/{previous/perma}/" class="prev">Previous</a>
			</xsl:if>
			<xsl:if test="string-length(next/perma) &gt; 0">
				<a href="/comic/{next/perma}/" class="next">Next</a>
				<a href="/comic/" class="last">Latest<xsl:text disable-output-escaping="yes"> &amp;raquo;</xsl:text></a>
			</xsl:if>
		</div>
		<img src="http://cmimg.dxprog.com/{image}" alt="{title}" />
		<div class="comic_nav bottom">
			<xsl:if test="string-length(previous/perma) &gt; 0">
				<a href="/comic/l4m0r-the-hedgehog/" class="first"><xsl:text disable-output-escaping="yes">&amp;laquo; </xsl:text>First</a>
				<a href="/comic/{previous/perma}/" class="prev">Previous</a>
			</xsl:if>
			<xsl:variable name="date"><xsl:value-of select="date" /></xsl:variable>
			<xsl:value-of select="php:function('date', 'F j, Y', $date)" />
			<xsl:if test="string-length(next/perma) &gt; 0">
				<a href="/comic/{next/perma}/" class="next">Next</a>
				<a href="/comic/" class="last">Latest<xsl:text disable-output-escaping="yes"> &amp;raquo;</xsl:text></a>
			</xsl:if>
		</div>
	</xsl:template>
	
</xsl:stylesheet>