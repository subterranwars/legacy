<!-- TemplateBeginsHere -->
<!-- TemplateBeginEditable name="nachrichten_detail_topic" -->
<form action="%PHP_SELF%" method="post">
<table>	
	<tr>
		<th></th>
		<th>Datum:</th>
		<th>%SPALTE%</th>
		<th>Betreff:</th>
	</tr>
	<!-- TemplateEndEditable -->
	<!-- TemplateBeginEditable name="nachrichten_detail" -->	
	<tr>
		<td><input type="checkbox" name="del[]" value="%ID%">
		<td class="%CLASS%" width=170>%DATUM%</td>
		<td class="%CLASS%">%ABSENDER%</td>
		<td class="%CLASS%">%BETREFF%</td>
	</tr>
	<!-- TemplateEndEditable -->
	<tr>
		<td colspan="2">
			<select name="submit_del">
				<option value="1">makierte Einträge</option>
				<option value="2">alle Einträge</option>
				<option value="3">gelesene Einträge</option>
			</select>
		</td>
		<td colspan="2">
			<input type="submit" name="to_do" value="löschen">
		</td>
	</tr>
</table>
</form>
<!-- TemplateEndsHere -->