<!-- TemplateBeginsHere -->
<!-- TemplateBeginEditable name="pw_change" -->
<form action="pw_change.php" method="post">
<table>
	<tr>
		<th colspan="2">
			:: Passwort ändern ::
		</th>
	</tr>
	<tr>
		<td colspan="2">
			%FEHLER%
			<div class="green">
				%BESTAETIGUNG%
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			Hier Kannst du dein Passwort verändern. Aus Sicherheitsgründen muss das 
			alte Passwort mit angegeben werden, um Missbrauch vorzubeugen!
		</td>
	</tr>
	<tr>
		<td>
			Altes Passwort:
		</td>
		<td>
			<input type="password" name="pw_old" size="30">
		</td>
	</tr>
	<tr>
		<td>
			Neues Passwort:
		</td>
		<td>
			<input type="password" name="pw_new" size="30">
		</td>
	</tr>
	<tr>
		<td>
			Neues Passwort Wiederholung:
		</td>
		<td>
			<input type="password" name="pw_new2" size="30">
		</td>
	</tr>
	<tr>
		<th colspan="2">
			<input type="submit" name="send" value="Passwort ändern">
		</th>
	</tr>
</table>
</form>
<!-- TemplateEndEditable -->
<!-- TemplateEndsHere -->