<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
	<xsl:output method="html" />
	<xsl:template match="/">
		<section id="qa">
			<img src="/themes/dx2010/images/me_2010.jpg" alt="It's-a Me" />
			<header>
				<h2>About the Prog</h2>
				<h3>Ask me anything and I shall answer.</h3>
			</header>
			<xsl:choose>
				<xsl:when test="/root/message">
					<p class="message"><xsl:value-of select="//message" /></p>
				</xsl:when>
				<xsl:otherwise>
					<form action="/about/" method="post">
						<textarea name="question" id="question"></textarea>
						<input type="hidden" name="checksum" id="checksum" />
						<button name="submit" tpye="submit">Submit Question</button>
					</form>
				</xsl:otherwise>
			</xsl:choose>
			<h3>Stuff I've answered thus far</h3>
			<xsl:for-each select="//content_item">
				<xsl:if test="string-length(body) &gt; 0">
					<article>
						<h4><xsl:value-of select="title" /></h4>
						<p><xsl:value-of select="body" /></p>
						<time datetime="{php:function('date', 'Ymd', string(date))}">Asked on <xsl:value-of select="php:function('date', 'F j, Y', string(date))" /></time>
					</article>
				</xsl:if>
			</xsl:for-each>
		</section>
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