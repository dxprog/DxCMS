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
		<h3>New Blog Item</h3>
		<form method="post" action="/admin/blog/create/" id="galleryItem">
			<xsl:if test="//id">
				<xsl:attribute name="action">/admin/blog/update/<xsl:value-of select="//id" />/</xsl:attribute>
			</xsl:if>
			<table class="form">
				<tr>
					<td><label for="title">Title:</label></td>
					<td><input type="text" name="title" id="title" value="{//title}" /></td>
				</tr>
				<tr>
					<td><label for="perma">Perma:</label></td>
					<td>
						<xsl:choose>
							<xsl:when test="//perma">
								<strong><xsl:value-of select="//perma" /></strong>
								<input type="hidden" name="perma" value="{//perma}" />
							</xsl:when>
							<xsl:otherwise>
								<input type="text" name="perma" id="perma" />
							</xsl:otherwise>
						</xsl:choose>
					</td>
				</tr>
				<tr>
					<td><label for="date">Date:</label></td>
					<td><input type="text" name="date" id="date" class="date" value="{//displayDate}" /></td>
				</tr>
				<tr>
					<td><label for="tags">Tags:</label></td>
					<td><input type="text" name="tags" id="tags" value="{$tags}" /></td>
				</tr>
				<tr>
					<td style="vertical-align:top;"><label for="body">Body:</label></td>
					<td><textarea id="body" name="body"><xsl:value-of select="//body" /></textarea></td>
				</tr>
				<tr>
					<td><label for="formatting">Disable Formatting (enables HTML):</label></td>
					<td><input type="checkbox" name="formatting" id="formatting" /></td>
				</tr>
				<tr>
					<td colspan="2"><button type="submit">Submit</button></td>
				</tr>
			</table>
			<input type="hidden" name="contentType" value="blog" />
		</form>
		<script type="text/javascript" src="/global/js/admin.js"></script>
	</xsl:template>
	
</xsl:stylesheet>