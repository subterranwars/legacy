<!-- TemplateBeginsHere -->
<!-- TemplateBeginEditable name="rohstoffgeb" -->	
<table>
	<tr>
		<th>
			:: Rohstoffgebäude ::
		</th>
	</tr>
	<tr>
		<td>
			In diesem Gebäude ist es möglich neben der Grundproduktion des Hauptquartiers
			weitere Grundrohstoffe (z.B. Stein, Öl, Eisen usw.) zu fördern. Dazu muss natürlich erst
			ein entsprechendes Vorkommen gesucht werden. 
			Nachdem dieser Schritt abgeschlossen ist, kann man mit sogennanten Drohnen 
			den Rohstoff eines Vorkommens abbauen.
			Die Drohnenanzahl kann beliebig variiert werden. Allerdings kann immer nur innerhalb
			eines bestimmten Zeitraumes die Drohnenanzahl geändert werden!
			Die Drohnenanzahl erhöht sich durch Ausbau des Rohstoffgebäudes!
		</td>
	</tr>
	<tr>
		<th>
			Neue Vorkommen suchen:
		</th>
	</tr>
	<tr>
		<td>
			<form action="geb_rohstoff.php" method="post">
			<table>
				<tr>
					<td>Rohstoff:</td>
					<td colspan="2">Wie lange soll gesucht werden?</td>
				<tr>
					<td>%AUSWAHL%</td>
					<td><input type="text" size="5" maxlength="11" name="dauer"></td>
					<td><input type="submit" value="suchen" name="send"></td>
				</tr>
				<tr>
					<td colspan="3">%FEHLER%</td>
				</tr>
				<tr>
					<td colspan=2>
						%VORKOMMEN_SUCHE%
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
	<tr>
		<th>
			Drohnenstatus:
		</th>
	</tr>
	<tr>
		<td>
			Vorhandene Drohnen: (%DROHNEN_ANZAHL%)<br>
			Davon benutzt:	(%DROHNEN_USED%)<br>
			unbenutzt:	(%DROHNEN_UNUSED%)
		</td>
	</tr>
	<tr>
		<th>
			Vorhandene Vorkommen
		</th>
	</tr>
	<tr>
		<td>
			%VORKOMMEN%
		</td>
	</tr>
</table>
<script language="JavaScript">
	//Vorkommenscounter startem
	CountDownVorkommen(%VORKOMMEN_ANZAHL%);
	//VorkommenssucheCounter starten!
</script>
<!-- TemplateEndEditable -->
<!-- TemplateEndsHere -->