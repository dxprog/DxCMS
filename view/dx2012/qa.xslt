<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
	<xsl:output method="html" />
	<xsl:template match="/">
		<div class="information">
			<img src="/themes/dx2010/images/me_2010.jpg" alt="It's-a Me" />
			<header>
				<h2>About Me</h2>
			</header>
			<article>
				<p>If you hadn't guessed by the header, my name is Matt Hackmann but it's also sometimes dxprog. Right now I am both.</p>
				<p>I am a web developer by trade, as a hobbyist since 2001 and professionally since 2009. It's a fascinating time to be in this field as more and more services go online and something new and exciting is being created everyday.</p>
				<p>When not web developing, I'm busy being a nerd of many hats. From computers to sci-fi to anime to retro gaming, I've got most of those bases covered (for good or ill). Sometimes I'll also dabble in art and video production.</p>
			</article>
		</div>
		<div class="questions">
			<header>
				<h3>Ask me anything and I shall answer</h3>
			</header>
			<xsl:choose>
				<xsl:when test="/root/message">
					<p class="message"><xsl:value-of select="//message" /></p>
				</xsl:when>
				<xsl:otherwise>
					<div class="form">
						<form action="/about/" method="post">
							<textarea name="question" id="question"></textarea>
							<input type="hidden" name="checksum" id="checksum" />
							<button name="submit" tpye="submit">Submit Question</button>
						</form>
					</div>
				</xsl:otherwise>
			</xsl:choose>
			<h3>Stuff I've answered thus far</h3>
			<xsl:for-each select="//content_item">
				<xsl:if test="string-length(body) &gt; 0">
					<article>
						<h4><xsl:value-of select="title" /></h4>
						<xsl:value-of select="body" disable-output-escaping="yes" />
						<time datetime="{php:function('date', 'Ymd', string(timestamp))}">Asked on <xsl:value-of select="php:function('date', 'F j, Y', string(timestamp))" /></time>
					</article>
				</xsl:if>
			</xsl:for-each>
		</div>
		<script type="text/javascript" src="/global/js/md5.js"></script>
		<script type="text/javascript">
			(function($) {
				$('form').submit(function(e) {
					var
					retVal = true,
					$question = $('#question');
					
					if ($.trim($question.val()).length === 0) {
						retVal = false;
					} else {
						$('#checksum').val(hex_md5($question.val()));
					}
					
					return retVal;
				})
			}(jQuery));
		</script>
	</xsl:template>
</xsl:stylesheet>