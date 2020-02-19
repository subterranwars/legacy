<!-- TemplateBeginsHere -->
<form action="geb_titanschmelze.php" method="post">
<table>
	<tr>
		<td>
			:: Titanschmelze ::
		</td>
	</tr>
	<tr>
		<td>
			Wandelt Titanerz nach Titan um :D
		</td>
	</tr>
	<tr>
		<td>
			Titanproduktion:
		</td>
	</tr>
	<tr>
		<td>
			<!-- TemplateBeginEditable name="titanschmelze" -->	
			<table>
				<tr>
					<td>Titanerzverbrauch pro Stunde:</td>
					<td>Titangewinn pro Stunde:</td>
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