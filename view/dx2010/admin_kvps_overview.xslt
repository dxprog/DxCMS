<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" />
	<xsl:template match="/">
		<table class="items">
			<thead>
				<tr>
					<th>Key Name</th>
					<th>Value</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<xsl:for-each select="//item">
					<tr>
						<xsl:if test="position() mod 2 = 0">
							<xsl:attribute name="class">even</xsl:attribute>
						</xsl:if>
						<td><xsl:value-of select="key" /></td>
						<td><xsl:value-of select="value" /></td>
						<td>
							<a href="/admin/kvps/edit/{key}/" class="edit">Edit</a> | 
							<a href="/admin/kvps/delete/{key}/" class="delete">Delete</a>
						</td>
					</tr>
				</xsl:for-each>
			</tbody>
		</table>
	</xsl:template>
	
</xsl:stylesheet>