<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	
	<xsl:template match="/">
		<form action="/admin/login/" method="post">
			<dl>
				<dt><label for="username">Username:</label></dt>
				<dd><input type="text" name="username" id="username" /></dd>
				<dt><label for="password">Password:</label></dt>
				<dd><input type="password" name="password" id="password" /></dd>
				<button type="submit">Login</button>
			</dl>
		</form>
	</xsl:template>
	
</xsl:stylesheet>