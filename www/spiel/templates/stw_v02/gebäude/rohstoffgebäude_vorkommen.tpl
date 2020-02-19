<!-- TemplateBeginsHere -->
<form action="geb_rohstoff.php" method="post">
	<table class="rohstoffe">
		<tr>
			<th>Auswahl:</th>
			<th>Rohstoff:</th>
			<th>Größe:</th>
			<th>verbleibend:</th>
			<th>Änderungen möglich?</th>
			<th>Drohnenanzahl:</th>
		<!-- TemplateBeginEditable name="vorkommen" -->
		<tr>
			<td colspan="6">
				<b>%TOPIC%</b>
			</td>
		</tr>
			<td><input type="checkbox" name="del[]" value="%ID%"></td>
			<td>%ROHSTOFF%</td>
			<td>%SIZE%</td>
			<td class="%CLASS%">%VERBLEIBEND%</td>
			<td><div id="%JS_ID%" title="%LAST_CHANGE%"></div></td>
			<td>
				%SELECT%
				%DROHNEN%
				<input type="hidden" value="%ID%" name="ID[]">
			</td>
		</tr>
		<tr>
			<td colspan="6">
				%FEHLER%
			</td>
		</tr>
		<!-- TemplateEndEditable -->
		<tr>
			<td colspan="3">
				<input type="submit" value="Vorkommen löschen" name="del_vorkommen">
			</td>
			<td align="right" colspan="3">
				<input type="submit" value="Drohnen setzen" name="send_drohnen">
			</td>
		</tr>
	</table>
</form>
<!-- TemplateEndsHere -->