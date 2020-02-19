<!-- TemplateBeginsHere -->
<form action="geb_extraktor.php" method="post">
<table>
	<tr>
		<th>
			:: Extraktor ::
		</th>
	</tr>
	<tr>
		<td>
			Wandelt Wasser in Wasserstoff um!
		</td>
	</tr>
	<tr>
		<td>
			Wasserstoffproduktion:
		</td>
	</tr>
	<tr>
		<td>
			<!-- TemplateBeginEditable name="extraktor" -->	
			<table>
				<tr>
					<td>Wasserverbrauch pro Stunde:</td>
					<td>Wasserstoffgewinn pro Stunde:</td>
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