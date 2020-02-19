<!-- TemplateBeginsHere -->
<!-- TemplateBeginEditable name="forschungszentrale" -->	
<form action="geb_forschungszentrale.php" method="post">
<table>
	<tr>
		<th>
			:: Forschungszentrale ::
		</th>
	</tr>
	<tr>
		<td>
			Die Forschungszentrale ist für jeden Herrscher unumgänglich.
			Mit ihr lassen sich zig Forschungen entdecken und für zivile, aber auch militärische
			Zwecke einsetzen.<br>
			Da allerdings nicht jeder Mann im Umgang mit Chemikalien, 
			radioaktiven Stoffen oder aber mit schon vorhandenen wissenschaftlichen 
			Erkenntnissen vertraut ist, müssen Wissenschaftler erst ausgebildet werden, 
			um Ihnen bei der Entdeckung neuer Technologien zur Seite stehen 
			zu können.<br><br>
			
			Bilden Sie also ein paar Wissenschaftler aus, welche dann eine gewisse Anzahl
			Forschungspunkte pro Stunde produzieren und diese Forschungspunkte können dann
			dazu verwendet werden, um gewisse Technologien zu erforschen.
		</td>
	</tr>
	<tr>
		<th>
			Forscher:
		</th>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<td>Forscher:</td>
					<td>%FORSCHER%</td>
				</tr>
				<tr>
					<td>Forschungspunkte:</td>
					<td>%PUNKTE%</td>
				</tr>
				<tr>
					<td>Produktion pro Stunde:</td>
					<td>%PRODUKTION%</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<th>Forscher ausbilden:</th>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<th>Kosten:</th>
					<th>Dauer:</th>
					<th>Ausbilden:</th>
				</tr>
				<tr>
					<td>
						%KOSTEN% Nahrung
					</td>
					<td>
						%DAUER%
					</td>
					<td>
						<input type="submit" name="send" value="ausbilden">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						%FEHLER%
					</td>
				</tr>
			</table>
		</td>
	</tr>	
	<tr>
		<th>
			%AUSBILDUNG%		
		</th>
	</tr>
</table>
</form>
<!-- TemplateEndEditable -->
<!-- TemplateEndsHere -->