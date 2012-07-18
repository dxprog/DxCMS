<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" />
	<xsl:template match="/">
		<div id="gallery">
			<xsl:for-each select="//content_item">
				<div>
					<a href="/entry/{perma}/" title="{title}">
						<img src="/thumb.php?file={meta/file}&amp;height=200" alt="{title}" />
					</a>
				</div>
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