<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" />
	<xsl:template match="/">
		<xsl:variable name="tags">
			<xsl:for-each select="//tags_item">
				<xsl:if test="position() &gt; 1">,</xsl:if>
				<xsl:value-of select="name" />
			</xsl:for-each>
		</xsl:variable>
		<h3>Create Featured Item</h3>
		<form method="post" action="/admin/featured/create/" id="galleryItem">
			<table class="form">
				<tr>
					<td><label for="contentId">Content:</label></td>
					<td>
						<select name="contentId" id="contentId">
							<xsl:for-each select="//content_item">
								<option value="{id}"><xsl:value-of select="title" /></option>
							</xsl:for-each>
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="image">Image:</label></td>
					<td>
						<input type="file" name="image" id="image" />
						<input type="hidden" name="image_file" id="image_file" value="{//meta/featured_image}" />
					</td>
				</tr>
				<tr>
					<td><label for="teaser">Teaser:</label></td>
					<td><input type="text" name="teaser" id="teaser" /></td>
				</tr>
				<tr colspan="2">
					<td><button type="submit">Submit</button></td>
				</tr>
			</table>
		</form>
		<script type="text/javascript" src="/global/js/admin.js"></script>
	</xsl:template>
	
</xsl:stylesheet>