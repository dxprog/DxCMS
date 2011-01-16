<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" />
	<xsl:template match="/">
		<form action="/admin/login/" method="post">
			<table>
				<tr>
					<td><label for="title">Title:</label></td>
					<td><input type="text" name="title" id="title" /></td>
				</tr>
				<tr>
					<td><label for="perma">Perma:</label></td>
					<td><input type="text" name="perma" id="perma" /></td>
				</tr>
				<tr>
					<td><label for="body">Body:</label></td>
					<td><textarea id="body" name="body"></textarea></td>
				</tr>
				<tr>
					<td><label for="tags">Tags:</label></td>
					<td><input type="text" name="tags" id="tags" /></td>
				</tr>
				<tr>
					<td colspan="2"><button type="submit">Submit</button></td>
				</tr>
			</table>
		</form>
		<script type="text/javascript">
			(function() {
				var
				createPerma = function(val) {
					var remove = /(\'|\"|\.|,|~|!|\?|&lt;|&gt;|@|#|\$|%|\^|&amp;|\*|\(|\)|\+|=|\/|\\|\||\{|\}|\[|\]|-|--)/ig;
					val = val.replace(remove, '');
					val = val.replace(/\s\s/g, ' ').replace(/\s/g, '-').toLowerCase();
					return val;
				},
				titleKeyUp = function() {
					$('#perma').val(createPerma($('#title').val()));
				},
				titleBlur = function() {
					
				},
				init = function() {
					$('#title').keyup(titleKeyUp).blur(titleBlur);
				};
				$(init);
			})();
		</script>
	</xsl:template>
	
</xsl:stylesheet>