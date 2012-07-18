<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
	<xsl:output method="html" />
	<xsl:template match="/">
		<nav>
			<ul>
				<xsl:for-each select="/nav/items/node()|@*">
					<li>
						<xsl:variable name="url">
							<xsl:value-of select="." />
						</xsl:variable>
						<xsl:if test="//page = .">
							<xsl:attribute name="class">selected</xsl:attribute>
						</xsl:if>
						<a href="{.}">
							<xsl:value-of select="name(.)" />
						</a>
					</li>
				</xsl:for-each>
			</ul>
		</nav>
	</xsl:template>	
</xsl:stylesheet>