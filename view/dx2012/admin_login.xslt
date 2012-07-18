<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	
	<xsl:template match="/">
		<form action="/admin/login/" method="post">
			<div class="login form">
				<h2>Administration Login</h2>
				<xsl:if test="count(//error) &gt; 0">
					<div class="error">
						<p><xsl:value-of select="//error" /></p>
					</div>
				</xsl:if>
				<div class="field">
					<label for="username">Username</label>
					<input type="text" name="username" id="username" />
				</div>
				<div class="field">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" />
				</div>
				<div class="buttons">
					<button type="submit">Login</button>
				</div>
			</div>
		</form>
	</xsl:template>
	
</xsl:stylesheet>