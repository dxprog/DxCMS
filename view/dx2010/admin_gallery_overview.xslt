<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" />
	<xsl:template match="/">
		<table class="items">
			<tr>
				<th class="name">Name</th>
				<th class="date">Date</th>
				<th class="type">Type</th>
				<th class="actions">Actions</th>
			</tr>
			<xsl:for-each select="//content_item">
				<tr>
					<xsl:if test="position() mod 2 = 0">
						<xsl:attribute name="class">even</xsl:attribute>
					</xsl:if>
					<td><a href="/entry/{perma}/" target="_blank"><xsl:value-of select="title" /></a></td>
					<td><xsl:value-of select="displayDate" /></td>
					<td class="type"><xsl:value-of select="meta/type" /></td>
					<td><a href="/admin/gallery/edit/{id}/" class="edit">Edit</a></td>
				</tr>
			</xsl:for-each>
		</table>
	</xsl:template>
</xsl:stylesheet>