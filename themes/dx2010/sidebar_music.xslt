<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">

	<xsl:template match="/">
		<aside id="music">
			<h2>Last Listened To</h2>
			<img alt="Album Artwork" src="{concat('/thumb.php?file=', //album_art)}" />
			<p>
				<a target="_blank">
					<xsl:attribute name="href">
						<xsl:value-of select="concat('http://www.google.com/search?q=', php:function('urlencode', string(concat(//track_title, ' ', //album_title))))" />
					</xsl:attribute>
					<strong><xsl:value-of select="//track_title" disable-output-escaping="yes" /></strong>
				</a>
			</p>
			<p>By <xsl:value-of select="//artist_name" disable-output-escaping="yes" /></p>
			<time datetime="2010-01-01T00:00:00">Listened to <xsl:value-of select="//rel_time" /> ago</time>
		</aside>
	</xsl:template>
	
</xsl:stylesheet>