<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
	<xsl:key name="years" match="archives_item" use="php:function('date', 'Y', string(timestamp))" />
	<xsl:output method="html" />
	<xsl:template match="/">
		<aside id="content_archives">
			<h3>Archive</h3>
			<ul>
				<xsl:for-each select="//archives/archives_item[generate-id() = generate-id(key('years', php:function('date', 'Y', string(timestamp)))[1])]">
					<li>
						<xsl:variable name="date"><xsl:value-of select="timestamp" /></xsl:variable>
						<xsl:variable name="contextDate"><xsl:value-of select="//context_date" /></xsl:variable>
						<xsl:variable name="year"><xsl:value-of select="php:function('date', 'Y', $date)" /></xsl:variable>
						<xsl:variable name="contextYear"><xsl:value-of select="php:function('date', 'Y', $contextDate)" /></xsl:variable>
						<a href="/archives/{$year}/">
							<xsl:value-of select="$year" />
						</a>
						<xsl:if test="$year = $contextYear">
							<xsl:variable name="startDate"><xsl:value-of select="php:function('mktime', 0, 0, 0, 1, 1, $year)" /></xsl:variable>
							<xsl:variable name="endDate"><xsl:value-of select="php:function('mktime', 0, 0, 0, 12, 31, $year)" /></xsl:variable>
							<ul class="months">
								<xsl:for-each select="//archives/archives_item[timestamp &gt;= $startDate and timestamp &lt;= $endDate]">
									<xsl:sort select="timestamp" data-type="number" order="ascending" />
									<li>
										<a href="/archives/{php:function('date', 'm', string(timestamp))}/{$year}/">
											<xsl:value-of select="php:function('date', 'F', string(timestamp))" />
										</a>
									</li>
								</xsl:for-each>
							</ul>
						</xsl:if>
					</li>
				</xsl:for-each>
			</ul>
		</aside>
	</xsl:template>
	
</xsl:stylesheet>