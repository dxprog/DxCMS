<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
	<xsl:output method="html" />
	<xsl:template match="/">
		<xsl:apply-templates select="content_articles/articles/articles_item" />
		<footer>
			<xsl:choose>
				<xsl:when test="content_articles/prev">
					<a href="{content_articles/prev}" class="older">Older Posts</a>
				</xsl:when>
			</xsl:choose>
			<xsl:choose>
				<xsl:when test="content_articles/next">
					<a href="{content_articles/next}" class="newer">Newer Posts</a>
				</xsl:when>
			</xsl:choose>
		</footer>
		<!-- SOCIAL INCLUDES -->
	</xsl:template>
	
	<xsl:template match="articles_item">
		<article>
			<header>
				<h3>
					<a href="/entry/{perma}" title="{title}">
						<xsl:value-of select="title" />
					</a>
				</h3>
				<div class="meta">
					<time datetime="{rfcDate}">
						<span class="day">
							<xsl:value-of select="day" />
						</span>
						<span class="month">
							<xsl:value-of select="month" />
							<span class="year">
								<xsl:value-of select="year" />
							</span>
						</span>
					</time>
					<ul class="tags">
						<xsl:apply-templates select="tags/tags_item/name" />
					</ul>
				</div>
			</header>
			<div class="body">
				<xsl:choose>
					<xsl:when test="type = 'art' or type = 'video' or type = 'portfolio'">
						<div class="mediaThumb">
							<img src="{meta/thumb}" alt="{title}" />
						</div>
					</xsl:when>
					<xsl:when test="type = 'comic'">
						<a class="comic" href="/comic/{perma}/">
							<div style="background-image:url(/comics/{comic_image});"></div>
							<span>View Full Comic</span>
						</a>
					</xsl:when>
				</xsl:choose>
				<xsl:value-of select="body" disable-output-escaping="yes" />
			</div>
			<xsl:choose>
				<xsl:when test="postBreak">
					<a href="/entry/{perma}#break" title="Continue Reading {title}" class="more">Read More</a>
				</xsl:when>
				<xsl:when test="type = 'art' or type = 'video'">
					<a href="/entry/{perma}/" title="View Full Image" class="more">View Full</a>
				</xsl:when>
			</xsl:choose>
			<footer>
				<ul>
					<li class="comments">
						<a href="/entry/{perma}#comments" title="View comments for {title}">
							<span class="label">Comments</span>
							<span class="count"><xsl:value-of select="children" /></span>
						</a>
					</li>
					<li class="twitter">
						<a href="http://twitter.com/share?url=http://dxprog.com/entry/{perma}/" target="_blank" class="twitter-share-button">Tweet This!</a>
					</li>
					<li class="facebook">
						<a href="http://www.facebook.com/sharer.php?u=http://dxprog.com/entry/{perma}/" target="_blank" class="facebook-share-button">Like This on Facebook</a>
					</li>
				</ul>
			</footer>
		</article>
	</xsl:template>
	
	<xsl:template match="name">
		<li>
			<a href="{concat(//contentType, 'tag/', php:function('rawurlencode', string(.)), '/')}"><xsl:value-of select="." /></a>
		</li>
	</xsl:template>
	
</xsl:stylesheet>