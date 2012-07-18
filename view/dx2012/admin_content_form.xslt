<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
	<xsl:output method="html" />
	<xsl:template match="/">
		<script type="text/javascript" src="/global/ckeditor/ckeditor.js"></script>
		<xsl:variable name="tags">
			<xsl:for-each select="//tags_item">
				<xsl:if test="position() &gt; 1">,</xsl:if>
				<xsl:value-of select="name" />
			</xsl:for-each>
		</xsl:variable>
		<form method="post" action="/admin/blog/create/" id="galleryItem">
			<xsl:if test="//id">
				<xsl:attribute name="action">/admin/blog/update/<xsl:value-of select="//id" />/</xsl:attribute>
			</xsl:if>
			<xsl:variable name="date"><xsl:choose><xsl:when test="//date"><xsl:value-of select="//date" /></xsl:when><xsl:otherwise><xsl:value-of select="php:function('time')" /></xsl:otherwise></xsl:choose></xsl:variable>
			<div class="form">
				<h3>New Content Item</h3>
				<div class="field">
					<label for="title">Title</label>
					<input type="text" name="title" id="title" value="{//title}" />
				</div>
				<div class="field">
					<label for="perma">Perma</label>
					<xsl:choose>
						<xsl:when test="//perma">
							<strong><xsl:value-of select="//perma" /></strong>
							<input type="hidden" name="perma" value="{//perma}" />
						</xsl:when>
						<xsl:otherwise>
							<input type="text" name="perma" id="perma" />
						</xsl:otherwise>
					</xsl:choose>
				</div>
				<div class="field">
					<label for="contentType">Content Type</label>
					<input type="text" name="contentType" id="contentType" maxlength="5" value="{//contentType}" />
				</div>
				<div class="field">
					<label for="date">Date</label>
					<input type="text" name="date" id="date" class="date" value="{php:function('date', 'm/d/Y h:i:s', $date)}" />
				</div>
				<div class="field">
					<label for="tags">Tags</label>
					<input type="text" name="tags" id="tags" value="{$tags}" />
				</div>
				<div class="field">
					<label for="body">Body</label>
					<textarea id="body" name="body"><xsl:value-of select="//body" /></textarea>
				</div>
				<div class="field">
					<label for="meta">Metadata (JSON string)</label>
					<input type="text" name="meta" id="meta" />
				</div>
				<div class="buttons">
					<button type="submit">Submit</button>
				</div>
			</div>
		</form>
		<script type="text/javascript" src="/global/js/admin.js"></script>
	</xsl:template>
	
</xsl:stylesheet>