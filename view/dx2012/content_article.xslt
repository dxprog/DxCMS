<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
	<xsl:output method="html" />
	<xsl:template match="/">
		<xsl:apply-templates select="content_article/post" />
		<script type="text/javascript" src="/global/js/md5.js"></script>
		<xsl:apply-templates select="content_article/comments" />
		<xsl:apply-templates select="content_article/user" />
	</xsl:template>
	
	<xsl:template match="post">
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
		<article class="post">
			<header>
				<h2>
					<a href="/entry/{perma}/" title="{title}">
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
						<div class="imageTease" data-id="{id}">
							<a href="{$link}" title="{title}">
								<img src="http://cmimg.dxprog.com/{image}" />
							</a>
						</div>
					</xsl:when>
					<xsl:when test="type = 'portfolio' or type = 'art'">
						<div class="flashBox">
							<xsl:choose>
								<xsl:when test="meta/fileType = 'swf'">
									<script type="text/javascript">
										$(function(){dx.flash({swf:'<xsl:value-of select="meta/file" />', width:<xsl:value-of select="meta/width" />, height:<xsl:value-of select="meta/height" />})});
									</script>
								</xsl:when>
								<xsl:when test="meta/fileType = 'flv' or meta/fileType = 'mp4'">
									<script type="text/javascript">
										$('.flashBox').video('<xsl:value-of select="meta/file" />');
									</script>
								</xsl:when>
								<xsl:otherwise>
									<a href="{meta/file}" target="_blank" class="enlarge">
										<img src="{meta/file}" alt="{title}" />
										<span>View Full Size</span>
									</a>
								</xsl:otherwise>
							</xsl:choose>
						</div>
					</xsl:when>
				</xsl:choose>
				<xsl:value-of select="body" disable-output-escaping="yes" />
				<xsl:choose>
					<xsl:when test="postBreak">
						<a href="/entry/{perma}#break" title="Continue Reading {title}" class="more">Read More</a>
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
			<a href="http://dxprog.com/tag/{.}/"><xsl:value-of select="." /></a>
		</li>
	</xsl:template>
	
	<xsl:template match="comments">
		<a name="comments"> </a>
		<section id="comments">
			<h3>Comments</h3>
			<xsl:apply-templates select="comments_item[count(meta/comment_parent) = 0]" />
		</section>
		<script type="text/javascript">
			dx.comments();
		</script>
	</xsl:template>
	
	<xsl:template match="comments_item">
		<xsl:variable name="id"><xsl:value-of select="id" /></xsl:variable>
		<article class="comment odd" data-id="{$id}">
			<xsl:if test="position() mod 2 = 0">
				<xsl:attribute name="class">comment even</xsl:attribute>
			</xsl:if>
			<header>
				<span class="user"><xsl:value-of select="meta/user_name" /></span>
			</header>
			<img src="{meta/user_avatar}" alt="{meta/user_name}" />
			<xsl:value-of select="body" disable-output-escaping="yes" />
			<footer>
				<time datetime="{rfcTime}">
					<xsl:value-of select="date" />
				</time>
				<a href="#addComment" class="commentReply">Reply</a>
			</footer>
		</article>
		<xsl:if test="count(//comments_item/meta[comment_parent = $id]) &gt; 0">
			<div class="children">
				<xsl:apply-templates select="//comments_item[meta/comment_parent = $id]" />
			</div>
		</xsl:if>
	</xsl:template>
	
	<xsl:template match="user">
		<script type="text/javascript" src="/global/js/md5.js"></script>
		<div id="addComment">
			<h3>Leave A Comment</h3>
			<form action="/post_comment/{//post/perma}/" method="post" id="commentForm">
				<div class="right">
					<xsl:choose>
						<xsl:when test="string-length(user_name) = 0">
							<label for="commentName">Name:</label>
							<input type="text" name="name" id="commentName" value="{user_name}" />
							<label for="commentEmail">E-mail (will not be shown):</label>
							<input type="text" name="email" id="commentEmail" value="{email}" />
						</xsl:when>
						<xsl:otherwise>
							<p>You are signed in as <strong><xsl:value-of select="user_name" /></strong></p>
						</xsl:otherwise>
					</xsl:choose>
					<label for="commentBody">Comment:</label>
					<textarea name="comment" id="commentBody"></textarea>
					<input type="hidden" name="botProof" id="botProof" />
					<input type="hidden" name="comment_parent" id="comment_parent" value="0" />
					<button type="submit">Submit Comment</button>
				</div>
			</form>
		</div>
	</xsl:template>
	
</xsl:stylesheet>