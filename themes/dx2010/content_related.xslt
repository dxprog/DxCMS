<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">
		<aside id="mostPopular">
			<h2>Related Posts</h2>
			<ul>
				<xsl:for-each select="//item">
					<li>
						<xsl:choose>
							<xsl:when test="position() = 1">
								<xsl:attribute name="class">first</xsl:attribute>
							</xsl:when>
							<xsl:when test="position() = 2">
								<xsl:attribute name="class">second</xsl:attribute>
							</xsl:when>
							<xsl:when test="position() = 3">
								<xsl:attribute name="class">third</xsl:attribute>
							</xsl:when>
							<xsl:when test="position() = 4">
								<xsl:attribute name="class">fourth</xsl:attribute>
							</xsl:when>
							<xsl:when test="position() = 5">
								<xsl:attribute name="class">fifth</xsl:attribute>
							</xsl:when>
						</xsl:choose>
						<a href="{concat('/entry/', perma)}">
							<xsl:value-of select="title" />
						</a>
					</li>
				</xsl:for-each>
			</ul>
			<!-- ENABLE CLICK ON ENTIRE LIST ELEMENT -->
			<script type="text/javascript">
				$('#mostPopular li').click(function(e) { if (!$(e.target).attr('href')) { window.location.href = $(e.target).find('a').attr('href'); }});
			</script>
		</aside>
	</xsl:template>
</xsl:stylesheet>