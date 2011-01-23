<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" />
	<xsl:template match="/">
		<xsl:choose>
			<xsl:when test="count(//results_item) &gt; 0">
				<p class="searchStats">Your search for <em>"<xsl:value-of select="//query" />"</em> returned <strong><xsl:value-of select="content_search/count" /></strong> results in <xsl:value-of select="//speed" /> seconds.</p>
				<ol class="search" start="{content_search/firstResult}">
					<xsl:apply-templates select="//results_item" />
				</ol>
			</xsl:when>
			<xsl:otherwise>
				<p class="searchStats">Sorry, your search for <strong>"<xsl:value-of select="//query" />"</strong> returned no results.</p>
			</xsl:otherwise>
		</xsl:choose>
		<footer>
			<xsl:choose>
				<xsl:when test="content_search/prev">
					<a href="{content_search/prev}" class="older">Back</a>
				</xsl:when>
			</xsl:choose>
			<xsl:choose>
				<xsl:when test="content_search/next">
					<a href="{content_search/next}" class="newer">Next</a>
				</xsl:when>
			</xsl:choose>
		</footer>
	</xsl:template>
	
	<xsl:template match="results_item">
		<li>
			<h3>
				<xsl:choose>
					<xsl:when test="type = 'comic'">
						<a href="/comic/{perma}/"><xsl:value-of select="title" /></a>
					</xsl:when>
					<xsl:otherwise>
						<a href="/entry/{perma}/"><xsl:value-of select="title" /></a>
					</xsl:otherwise>
				</xsl:choose>
			</h3>
			<h4>Posted on <em><xsl:value-of select="date" /></em></h4>
			<p><xsl:value-of select="body" /></p>
			<xsl:if test="type = 'art' or type = 'portfolio' or type = 'video'">
				<img src="{meta/thumb}" alt="{title}" />
			</xsl:if>
		</li>
	</xsl:template>
	
</xsl:stylesheet>