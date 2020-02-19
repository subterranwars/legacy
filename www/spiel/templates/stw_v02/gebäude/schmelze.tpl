<!-- TemplateBeginsHere -->
<form action="geb_schmelze.php" method="post">
<table>
	<tr>
		<td>
			:: Schmelze ::
		</td>
	</tr>
	<tr>
		<td>
			Hier wird aus Eisen Stahl gewonnen.
		</td>
	</tr>
	<tr>
		<td>
			Stahlproduktion:
		</td>
	</tr>
	<tr>
		<td>
			<!-- TemplateBeginEditable name="schmelze" -->	
			<table>
				<tr>
					<td>Eisenverbrauch pro Stunde:</td>
					<td>Stahlgewinn pro Stunde:</td>
					<td>Auslastung:</td>
				<tr>
					<td>%VERBRAUCH%</td>
					<td>%GEWINN%</td>
					<td>
						%SELECT%
					</td>							
				</tr>
				<tr>
					<td colspan="3">
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