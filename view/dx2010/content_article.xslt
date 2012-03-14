<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" />
	<xsl:template match="/">
		<xsl:apply-templates select="content_article/post" />
		<script type="text/javascript" src="/global/js/md5.js"></script>
		<xsl:apply-templates select="content_article/comments" />
		<xsl:apply-templates select="content_article/user" />
	</xsl:template>
	
	<xsl:template match="post">
		<xsl:choose>
			<xsl:when test="type = 'art' or type = 'portfolio'">
				<div class="gallerySingle">
					<xsl:choose>
						<xsl:when test="meta/fileType = 'swf'">
							<script type="text/javascript">
								$(function(){dx.flash({swf:'<xsl:value-of select="meta/file" />', width:<xsl:value-of select="meta/width" />, height:<xsl:value-of select="meta/height" />})});
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
			<xsl:when test="type = 'video'">
				<div class="gallerySingle">
					<script type="text/javascript">
						$('.gallerySingle').video('<xsl:value-of select="meta/file" />');
					</script>
				</div>
			</xsl:when>
		</xsl:choose>
		<article>
			<header>
				<h3>
					<a href="/entry/{perma}/" title="{title}">
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
				<xsl:value-of select="body" disable-output-escaping="yes" />
				<xsl:if test="type = 'poll'">
					<xsl:choose>
						<xsl:when test="voted = '1'">
							<div class="graph">
								<h4><xsl:value-of select="title" /></h4>
								<ul>
									<xsl:for-each select="meta/meta_item">
										<li>
											<span><xsl:value-of select="title" /></span>
											<xsl:if test="percent &gt; 0">
												<div class="bar" style="width:{percent div 2}%"></div>
											</xsl:if>
											<div class="data"><xsl:value-of select="percent" />% - <xsl:value-of select="votes" /> vote<xsl:if test="votes != '1'">s</xsl:if></div>
										</li>
									</xsl:for-each>
								</ul>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<form action="/poll/vote/{perma}/" method="post" name="poll{id}" id="poll{id}" class="poll">
								<h4><xsl:value-of select="title" /></h4>
								<ul>
									<xsl:for-each select="meta/meta_item">
										<li>
											<input type="radio" value="{id}" name="poll_option" id="poll{id}_option{position()}" />
											<label for="poll{id}_option{position()}"><xsl:value-of select="title" /></label>
										</li>
									</xsl:for-each>
								</ul>
								<button type="submit">Vote</button>
							</form>
							<script type="text/javascript">dx.poll(<xsl:value-of select="id" />);</script>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:if>
			</div>
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
				Posted on
				<time datetime="{rfcTime}">
					<xsl:value-of select="date" />
				</time>
				by
				<span class="user"><xsl:value-of select="meta/user_name" /></span>
			</header>
			<img src="{meta/user_avatar}" alt="{meta/user_name}" />
			<xsl:value-of select="body" disable-output-escaping="yes" />
			<a href="#addComment" class="commentReply">Reply</a>
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
			<h3>Post A Comment</h3>
			<form action="/post_comment/{//post/perma}/" method="post" id="commentForm">
				<div class="left">
					<img src="{avatar}" alt="Avatar" class="avatar" />
					<xsl:choose>
						<xsl:when test="showSignIn = 'true'">
							<input type="hidden" name="avatar" value="{avatar}" />
							<a href="/auth_facebook.php" title="Login with Facebook">
								<img src="/themes/dx2010/images/login_fb.png" alt="Login with Facebook" />
							</a>
							<a href="/auth_twitter.php?redirect" title="Login with Twitter">
								<img src="/themes/dx2010/images/login_twitter.png" alt="Login with Twitter" />
							</a>
						</xsl:when>
						<xsl:otherwise>
							<a href="/auth_{auth_type}.php?signout">Signout</a>
						</xsl:otherwise>
					</xsl:choose>
				</div>
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