<!-- TemplateBeginsHere -->
<form action="geb_chemiefabrik.php" method="post">
<table>
	<tr>
		<th>
			:: Chemiefabrik ::
		</th>
	</tr>
	<tr>
		<td>
			Dieses Gebäude wandelt in einem hochkomplexen Verfahren Öl in Kunststoff um!
		</td>
	</tr>
	<tr>
		<td>
			Kunstoffproduktion:
		</td>
	</tr>
	<tr>
		<td>
			<!-- TemplateBeginEditable name="chemiefabrik" -->	
			<table>
				<tr>
					<td>Ölverbrauch pro Stunde:</td>
					<td>Kunststoffgewinn pro Stunde:</td>
					<td>Auslastung:</td>
				<tr>
					<td>%VERBRAUCH%</td>
					<td>%GEWINN%</td>
					<td>
						%SELECT%
					</td>							
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