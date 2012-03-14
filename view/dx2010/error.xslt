<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">
		<section id="error">
			<img src="/images/HanakoError.jpg" alt="You've made poor Hanako cry!" />
			<h2>Oh no!</h2>
			<p>
				<xsl:value-of select="//message" />
			</p>
			<xsl:if test="string-length(//code) &gt; 0 and //code != 404">
				<p>
					The system's dying words were: <xsl:value-of select="//code" />
				</p>
			</xsl:if>
		</section>
	</xsl:template>
</xsl:stylesheet>