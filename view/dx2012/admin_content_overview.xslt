<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
	<xsl:output method="html" />
	<xsl:template match="/">
		<div class="overview">
			<xsl:variable name="contentType"><xsl:value-of select="//content_type" /></xsl:variable>
			<h2>
				<xsl:value-of select="php:function('ucfirst', $contentType)" /> Admin
				<a href="/admin/{$contentType}/new/" class="add">
					Add New
				</a>
			</h2>
			<div class="search">
				<form action="/admin/{$contentType}/search/" method="GET">
					<input type="text" name="q" />
					<button type="submit">Search</button>
				</form>
			</div>
			<table>
				<thead>
					<tr>
						<th class="name">Name</th>
						<th class="date">Date</th>
						<th class="actions">Actions</th>
					</tr>
				</thead>
				<tbody>
					<xsl:for-each select="//content_item">
						<xsl:variable name="date"><xsl:value-of select="date" /></xsl:variable>
						<tr>
							<td><a href="/entry/{perma}/" target="_blank"><xsl:value-of select="title" /></a></td>
							<td class="date"><xsl:value-of select="php:function('date', 'M j, Y n:ia', $date)" /></td>
							<td>
								<a href="/admin/{$contentType}/edit/{id}/" class="edit">Edit</a> | 
								<a href="/admin/{$contentType}/delete/{id}/" class="delete">Delete</a>
							</td>
						</tr>
					</xsl:for-each>
				</tbody>
			</table>
		</div>
	</xsl:template>
</xsl:stylesheet>