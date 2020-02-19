<!-- TemplateBeginsHere -->
<form action="geb_brutreaktor.php" method="post">
<table>
	<tr>
		<th>
			:: Brutreaktor ::
		</th>
	</tr>
	<tr>
		<td>
			In dem Brutreaktor wird mittels Kernspaltung Energie erzeugt.<br>
			Bei diesem Vorgang fällt Plutonium als Abfallprodukt an.
		</td>
	</tr>
	<tr>
		<td>
			Plutoniumproduktion:
		</td>
	</tr>
	<tr>
		<td>
			<!-- TemplateBeginEditable name="brutreaktor" -->	
			<table>
				<tr>
					<td>Uranverbrauch</td>
					<td>Plutoniumgewinn</td>
					<td>Energiegewinn</td>
					<td>Auslastung:</td>
				</tr>
				<tr>
					<td>%VERBRAUCH%</td>
					<td>%GEWINN%</td>
					<td>%ENERGIE%</td>
					<td>%SELECT%</td>							
				</tr>
				<tr>
					<td>
						%FEHLER%
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<input type="submit" value="Daten speichern" name="send">
					</td>
				</tr>
			</table>
			<!-- TemplateEndEditable -->
		</td>
	</tr>
</table>

</form>

<!-- TemplateEndsHere -->