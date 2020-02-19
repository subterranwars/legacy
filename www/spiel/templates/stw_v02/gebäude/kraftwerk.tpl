<!-- TemplateBeginsHere -->
<form action="geb_kraftwerk.php" method="post">
<table>
	<tr>
		<th>
			:: Kraftwerk ::
		</th>
	</tr>
	<tr>
		<td>
			In diesem Gebäude wird Energie produziert.
			Damit Energie erzeugt werden kann, muss Öl verbrannt werden.
			Je höher die Ausbaustufe des Kraftwerks, desto mehr Energie wird pro Stunde erzeugt.
			Ausserdem ist es möglich, das Kraftwerk nur auf 10% Auslastung zu fahren, um Rohstoffe zu sparen
			und nur die benötigte Energie zu produzieren.<br>
			Aber <b>Achtung!!!</b> Sobald der Energieverbrauch höher ist, als die erzeugte Energie
			brauchen Bauauftrage ein vielfaches mehr, bis sie beendet werden und Rohstoffe werden
			nur noch sehr langsam zu Tage gefördert!<br><br>
			Deshalb ist es wichtig, dass ein richtiges Rohstoffmanagement betrieben wird!
		</td>
	</tr>
	<tr>
		<td>
			Produzierte Energie
		</td>
	</tr>
	<tr>
		<td>
			<!-- TemplateBeginEditable name="kraftwerk" -->	
			<table>
				<tr>
					<td>Ölverbrauch pro Stunde:</td>
					<td>Energiegewinn pro Stunde:</td>
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