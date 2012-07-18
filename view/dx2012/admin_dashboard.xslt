<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
	<xsl:output method="html" />
	<xsl:template match="/">
		<div class="overview">
			<h2>Recent Comments</h2>
			<table>
				<thead>
					<tr>
						<th class="parent">Parent</th>
						<th class="poster">Poster</th>
						<th class="body">Body</th>
						<th class="date">Date</th>
					</tr>
				</thead>
				<tbody>
					<xsl:for-each select="//comments/content/content_item">
						<xsl:variable name="date"><xsl:value-of select="date" /></xsl:variable>
						<tr>
							<td class="parent"><xsl:value-of select="parent" /></td>
							<td class="poster"><xsl:value-of select="meta/user_name" /></td>
							<td class="body"><xsl:value-of select="body" /></td>
							<td class="date"><xsl:value-of select="php:function('date', 'M j, Y n:ia', $date)" /></td>
						</tr>
					</xsl:for-each>
				</tbody>
			</table>
		</div>
	</xsl:template>
	
</xsl:stylesheet>