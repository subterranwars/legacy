<!-- TemplateBeginsHere -->
<form action="bauplan_erstellen.php?action=antrieb" method="post">
<table>
	<tr>
		<th colspan="8">
			Chassisauswahl:
		</th>
	</tr>
	<tr>
		<th colspan="2">Bezeichnung:</th>
		<th>Lebenspunkte:</th>
		<th>max. Leistung:</th>
		<th>max. Zuladung:</th>
		<th>Geschwindigkeit:</th>
		<th>Wendigkeit:</th>
		<th>Zielen:</th>
	</tr>
	<!-- TemplateBeginEditable name="bauplan_chassis" -->
	<tr>
		<th>
			<input type="radio" name="chassis" value="%ID%">
		</th>
		<th>
			 %NAME%
		</th>
		<td>%HP%</td>
		<td>%MAX_LEISTUNG%</td>
		<td>%MAX_ZULADUNG%</td>
		<td>%GESCHWINDIGKEIT%</td>
		<td>%WENDIGKEIT%</td>
		<td>%ZIELEN%</td>
	</tr>
	<!-- TemplateEndEditable -->
	<tr>
		<td colspan="8">
			<input type="submit" value="Chassis auswählen" name="but_chassis">
		</td>
	</tr>
</table>
</form>
<!-- TemplateEndsHere -->