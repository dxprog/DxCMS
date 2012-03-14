<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" />
	<xsl:template match="/">
		<div id="gallery">
			<xsl:for-each select="//content_item">
				<a href="/entry/{perma}/" title="{title}">
					<xsl:if test="position() mod 5 = 0">
						<xsl:attribute name="class">rowLast</xsl:attribute>
					</xsl:if>
					<img src="{meta/thumb}" alt="{title}" />
					<p>
						<xsl:value-of select="title" />
					</p>
				</a>
			</xsl:for-each>
		</div>
		<div id="galleryBg">
			<div id="galleryItem">
				<a href="javascript:void(0)" class="close">Close</a>
			</div>
		</div>
		<script type="text/javascript">
			dx.gallery();
		</script>
	</xsl:template>
	
</xsl:stylesheet>