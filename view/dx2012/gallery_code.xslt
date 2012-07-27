<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
	<xsl:output method="html" />
	<xsl:template match="/">
		<div id="gallery" class="portfolio">
			<h2>Random Code Projects</h2>
			<p>Below are various random code projects that deserve some sort of mention but are not necessarily portfolio worthy at this time. Any code available is open sourced under the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU GPL v3 license</a>, so you are free to do with the code as you wish. If you do use any of the below code, a mention and link back to this site is always nice :).</p>
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
					<img src="/thumb.php?file={meta/thumb}&amp;height=200" alt="{title}" />
					<h3>
						<xsl:value-of select="title" />
					</h3>
					<time datetime="{php:function('date', 'Y-d-m\Th:i:sP', $date)}">
						<xsl:value-of select="php:function('date', 'Y', $date)" />
					</time>
					<div class="description">					
						<xsl:if test="string-length(meta/github) &gt; 0 and meta/github != 'null'">
							<p><strong>Github: </strong><a href="{meta/github}" target="_blank">Repository</a></p>
						</xsl:if>
						<xsl:if test="string-length(meta/source) &gt; 0 and meta/source != 'null'">
							<p><strong>Source: </strong><a href="{meta/source}" target="_blank">Download</a></p>
						</xsl:if>
						<xsl:if test="string-length(meta/demo) &gt; 0 and meta/demo != 'null'">
							<p><strong>Demo: </strong><a href="{meta/demo}" target="_blank">View</a></p>
						</xsl:if>
						<xsl:value-of select="body" disable-output-escaping="yes" />
					</div>
				</div>
			</xsl:for-each>
		</div>
	</xsl:template>
	
</xsl:stylesheet>