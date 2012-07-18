<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
	<xsl:output method="html" />
	<xsl:template match="/">
		<xsl:apply-templates select="content_articles/articles/articles_item[type != 'portfolio']" />
		<footer>
			<xsl:choose>
				<xsl:when test="content_articles/prev">
					<a href="{content_articles/prev}" class="older"><xsl:text disable-output-escaping="yes">&amp;laquo;</xsl:text> Older Posts</a>
				</xsl:when>
			</xsl:choose>
			<xsl:choose>
				<xsl:when test="content_articles/next">
					<a href="{content_articles/next}" class="newer">Newer Posts <xsl:text disable-output-escaping="yes">&amp;raquo;</xsl:text></a>
				</xsl:when>
			</xsl:choose>
		</footer>
		<!-- SOCIAL INCLUDES -->
	</xsl:template>
	
	<xsl:template match="articles_item">
		<xsl:variable name="date">
			<xsl:value-of select="timestamp" />
		</xsl:variable>
		<xsl:variable name="link">
			<xsl:choose>
				<xsl:when test="type = 'art'">/gallery/<xsl:value-of select="perma" />/</xsl:when>
				<xsl:when test="type = 'comic'">/comic/<xsl:value-of select="perma" />/</xsl:when>
				<xsl:otherwise>/entry/<xsl:value-of select="perma" />/</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		
		<article class="post" data-id="{id}" data-type="{type}">
			<header>
				<h2>
					<a href="{$link}" title="{title}">
						<xsl:value-of select="title" disable-output-escaping="yes" />
					</a>
				</h2>
				<dl>
					<dt>Posted On</dt>
					<dd class="time">
						<time datetime="{php:function('date', 'Y-d-m\Th:i:sP', $date)}">
							<xsl:value-of select="concat(php:function('date', 'Y', $date), ' &amp;middot ', php:function('date', 'm', $date), ' &amp;middot ', php:function('date', 'd', $date))" disable-output-escaping="yes" />
						</time>
					</dd>
					<xsl:if test="count(tags/tags_item) &gt; 0">
						<dt class="tags">Tags</dt>
						<xsl:for-each select="tags/tags_item/name">
							<dd>
								<a href="/tag/{.}">
									<xsl:value-of select="." />
								</a>
							</dd>
						</xsl:for-each>
					</xsl:if>
				</dl>
			</header>
			<div class="body">
				<xsl:choose>
					<xsl:when test="type = 'art'">
						<div class="imageTease artwork" data-id="{id}">
							<a href="{$link}" title="{title}">
								<img src="{meta/file}" />
							</a>
						</div>
					</xsl:when>
					<xsl:when test="type = 'comic'">
						<div class="imageTease artwork" data-id="{id}">
							<a href="{$link}" title="{title}">
								<img src="http://cmimg.dxprog.com/{image}" />
							</a>
						</div>
					</xsl:when>
				</xsl:choose>
			
				<xsl:value-of select="body" disable-output-escaping="yes" />
				<xsl:choose>
					<xsl:when test="postBreak">
						<a href="/entry/{perma}#break" title="Continue Reading {title}" class="more">Read More</a>
					</xsl:when>
					<xsl:when test="type = 'art' or type = 'video'">
						<a href="/entry/{perma}/" title="View Full Image" class="more">View Full</a>
					</xsl:when>
				</xsl:choose>
			</div>
			<footer>
				<a href="/entry/{perma}/#comments">
					<xsl:choose>
						<xsl:when test="children != 0">
							<xsl:value-of select="children" /> comment<xsl:if test="children != 1">s</xsl:if>
						</xsl:when>
						<xsl:otherwise>
							Leave a Comment
						</xsl:otherwise>
					</xsl:choose>
				</a>
			</footer>
		</article>
	</xsl:template>
	
	<xsl:template match="name">
		<li>
			<a href="{concat(//contentType, 'tag/', php:function('rawurlencode', string(.)), '/')}"><xsl:value-of select="." /></a>
		</li>
	</xsl:template>
	
</xsl:stylesheet>