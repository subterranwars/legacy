<!-- TemplateBeginsHere -->
<!-- TemplateBeginEditable name="mission_koordinaten" -->
<form action="mission.php?action=spezial" method="post">
<table>
	<tr>
		<th colspan="2">Koordinatenauswahl</th>
	</tr>
	<tr>
		<td>
			Quelle:
		</td>
		<td>
			<input type="text" size="3" value="%X%" disabled>:<input type="text" size="3" value="%Y%" disabled>:<input type="text" size="3" value="%Z%"  disabled>
			<input type="hidden" value="%X%" name="xs">
			<input type="hidden" value="%Y%" name="ys">
			<input type="hidden" value="%Z%" name="zs">
		</td>
	</tr>
	<tr>
		<td>
			Ziel:
		</td>
		<td>
			<input type="text" size="3" value="%X%" onkeyup="changeMissionsdaten()" name="x">:<input type="text" size="3" value="%Y%" onkeyup="changeMissionsdaten()" name="y">:<input type="text" size="3" value="%Z%" onkeyup="changeMissionsdaten()" name="z">
		</td>
	</tr>
	<tr>
		<td>
			Geschwindigkeit:
		</td>
		<td>
			<select onChange="changeMissionsdaten()" name="geschwindigkeit">
				<option value="0.1">10%
				<option value="0.2">20%
				<option value="0.3">30%
				<option value="0.4">40%
				<option value="0.5">50%
				<option value="0.6">60%
				<option value="0.7">70%
				<option value="0.8">80%
				<option value="0.9">90%
				<option value="1" selected>100%
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Max Geschwindigkeit:
		</td>
		<td>
			%MAX% km/h
			<input type="hidden" value="%MAX%" id="max_geschwindigkeit">
		</td>
	</tr>
	<tr>
		<td>
			Entfernung:
		</td>
		<td id="entfernung">
			%ENTFERNUNG% km
		</td>
	</tr>
	<tr>
		<td>
			Dauer (einer Strecke)
		</td>
		<td id="dauer">
			%DAUER%
		</td>
	</tr>
	<tr>
		<td>Ankunft:</td>
		<td id="ankunft">%ANKUNFT%</td>
	</tr>
		<td>Rückkehr:</td>
		<td id="rueckkehr">%RÜCKKEHR%</td>
	</tr>
	<tr>
		<th colspan="2">
			<input type="submit" name="m2" value="weiter">
		</th>
	</tr>
</table>
</form>
<!-- TemplateEndEditable -->
<!-- TemplateEndsHere -->