<!-- TemplateBeginsHere -->
<!-- TemplateBeginEditable name="login" -->
<form action="login.php" method="POST">
	<table width="350">
		<tr>
			<th class="topic" colspan="2">
				:: login ::
			</th>
		</tr>
		<tr>
			<td colspan="2">
				%FEHLER%
			</td>
		</tr>
		<tr>
			<th class="row_topic">
				Login:
			</th>
			<td>
				<input type="text" name="stw_login" size="25" maxlength="50">
			</td>
		</tr>
		<tr>
			<th class="row_topic">
				Passwort:
			</th>
			<td>
				<input type="password" name="stw_password" size="25" maxlength="50">
			</td>
		</tr>
		<tr>
			<th colspan="2">
				<input type="submit" name="login" value="login">
			</th>
		</tr>
		<tr>
			<th>
				<a href="register.php">registrieren</a>
			</th>
			<th>
				<a href="lost_pw.php">Passwort vergessen?</a>
			</th>
		</tr>
	</table>
</form>
<!-- TemplateEndEditable -->
<!-- TemplateEndsHere -->