<!-- TemplateBeginsHere -->
<form action="geb_hq.php" method="post">
<table>
	<tr>
		<th>
			:: Hauptquartier ::
		</th>
	</tr>
	<tr>
		<td>
			Das Hauptquartier stellt quasi das Hauptgebäude jeder Kolonie da.
			Man könnte es als Rathaus betrachten. Hier finden die 
			Verwaltungsarbeiten statt und sorgen dafür, dass die
			Kolonie wachsen und gedeihen kann.<br><br>
			Aus diesem Grund ist es möglich, mit ausreichender Ausbaustufe
			dein Dorf im Hauptquartier zu einer Stadt oder später auch zu einer Metropole
			ausbauen zu lassen.
		</td>
	</tr>
	<tr>
		<th>
			Ausbau:
		</th>
	</tr>
	<tr>
		<td>
			<!-- TemplateBeginEditable name="hq" -->	
			<table>
				<tr>
					<th>Aktuelle Stufe:</th>
					<th>nächste Stufe:</th>
					<th>Kosten:</th>
					<th>Dauer:</th>
					<th>Upgrade:</th>
				</tr>
				</tr>
					<td valign="top">
						<b>%AKTUELLE_STUFE%</b>
					</td>
					<td valign="top">
						<b>%NAECHSTE_STUFE%</b>
						<div class="beschreibung">
							benötigt:<br>
							%REQUIREMENT%
						</div>
					</td>
					<td valign="top">
						%KOSTEN%
					</td>
					<td>
						%DAUER%
					</td>
					<td>
						%BAU%	
					</td>
				</tr>
				<tr>
					<td colspan="5">
						%FEHLER%
					</td>
			</table>
			<!-- TemplateEndEditable -->
		</td>
	</tr>
</table>
</form>
<!-- TemplateEndsHere -->