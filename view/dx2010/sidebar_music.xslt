<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">

	<xsl:template match="/">	
		<aside id="music">
			<h2>Last Listened To</h2>
			<img alt="Album Artwork" src="{concat('/thumb.php?file=', //images/art)}" />
			<p>
				<a target="_blank">
					<xsl:attribute name="href">
						<xsl:value-of select="concat('http://www.google.com/search?q=', php:function('urlencode', string(concat(//song_title, ' ', //album_title))))" />
					</xsl:attribute>
					<strong><xsl:value-of select="//song_title" disable-output-escaping="yes" /></strong>
				</a>
			</p>
			<xsl:if test="string-length(//artist_name) &gt; 0">
				<p>By <xsl:value-of select="//artist_name" disable-output-escaping="yes" /></p>
			</xsl:if>
			<time datetime="2010-01-01T00:00:00">Listened to <xsl:value-of select="//rel_time" /> ago</time>
		</aside>
	</xsl:template>
	
</xsl:stylesheet>