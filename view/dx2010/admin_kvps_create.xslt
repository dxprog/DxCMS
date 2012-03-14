<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" />
	<xsl:template match="/">
		<form action="/admin/kvps/create/" method="post">
			<xsl:if test="//key">
				<xsl:attribute name="action">/admin/kvps/update/<xsl:value-of select="//key" />/</xsl:attribute>
			</xsl:if>
			<table class="form">
				<tr>
					<td><label for="key">Key Name:</label></td>
					<td>
						<xsl:choose>
							<xsl:when test="//key">
								<strong><xsl:value-of select="//key" /></strong>
								<input type="hidden" name="key" value="{//key}" />
							</xsl:when>
							<xsl:otherwise>
								<input type="text" name="key" id="key" maxlength="30" />
							</xsl:otherwise>
						</xsl:choose>
					</td>
				</tr>
				<tr>
					<td><label for="value">Value:</label></td>
					<td><input type="text" name="value" id="value" value="{//value}" /></td>
				</tr>
			</table>
			<button type="submit">Submit</button>
		</form>
	</xsl:template>
	
</xsl:stylesheet>