<!-- TemplateBeginsHere -->
<table width="100%">
	<tr>			
		<th colspan="2">&nbsp;</th>
		<th>HP</th>
		<th>Angriff:</th>
		<th>Panzerung:</th>
		<th>Vmax:</th>
		<th>Wendigkeit:</th>
		<th>Zielen:</th>
		<th>Bauzeit:</th>
		<th>Anzahl:</th>
	</tr>
	<!-- TemplateBeginEditable name="bauplan" -->	
	<tr>
		<th><a href="geb_kaserne.php?ID=%ID%&show=%SHOW%" title="Profil löschen"><img src="templates/stw_v02/images/error.png" width="10"></a></th>
		<th><a href="#" onClick="PopUp('geb_kaserne_detail.php?ID=%ID%', 300, 750)">%NAME%</a></th>
		<td align="right">%HP%</td>
		<td align="right">%ANGRIFF%</td>
		<td align="right">%VERTEIDIGUNG%</td>
		<td align="right">%GESCHWINDIGKEIT% km/h</td>
		<td align="right">%WENDIGKEIT%</td>
		<td align="right">%ZIELEN%</td>
		<td align="right">%BUILDTIME%</td>
		<td align="right">
			<input type="text" size="3" name="anzahl[]">
			<input type="hidden" name="ID[]" value="%ID%">	
		</td>
	</tr>
	<!-- TemplateEndEditable -->
	<tr>
		<td align="center" colspan="10">
			<input type="submit" value="ausbilden" name="send">
		</td>
	</tr>
</table>
<!-- TemplateEndsHere -->