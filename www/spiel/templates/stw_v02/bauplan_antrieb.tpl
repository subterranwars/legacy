<!-- TemplateBeginsHere -->
<form action="bauplan_erstellen.php?action=equipment" method="post">
<table>
	<tr>
		<th colspan="6">
			Antriebsauswahl:
		</th>
	</tr>
	<tr>
		<th colspan="2">Bezeichnung:</th>
		<th>Leistung:</th>
		<th>Zuladung:</th>
		<th>Geschwindigkeit:</th>
		<th>Wendigkeit:</th>
	</tr>
	<!-- TemplateBeginEditable name="bauplan_antrieb" -->
	<tr>
		<th>
			<input type="radio" name="antrieb" value="%ID%">
		</th>
		<th>
			 %NAME%
		</th>
		<td>%LEISTUNG%</td>
		<td>%ZULADUNG%</td>
		<td>%GESCHWINDIGKEIT%</td>
		<td>%WENDIGKEIT%</td>
	</tr>
	<!-- TemplateEndEditable -->
	<tr>
		<td colspan="6">
			<input type="submit" value="Antrieb wählen" name="but_antrieb">
		</td>
	</tr>
</table>
</form>
<!-- TemplateEndsHere -->