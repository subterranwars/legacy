<!-- TemplateBeginsHere -->
<form action="truppenverband.php" method="post">
<table>
	<tr>
		<th>&nbsp;</th>
		<th colspan="2">HP</th>
		<th>Erfahrung</th>
		<th>Angriff</th>
		<th>Panzerung</th>
		<th>Zielen</th>
		<th>Vmax</th>
		<th>&nbsp;</th>
	</tr>
	<!-- TemplateBeginEditable name="truppenverband_einheiten" -->
	<tr>
		<th>
			%NAME%
			<div class="beschreibung">
				(%KLASSE%)
			</div>
		</th>
		<td>
			<img src="templates/stw_v02/images/hp.gif" width="%WIDTH%" height="3">
		</td>
		<td align="right">
			%HP%
		</td>
		<td align="right">
			%ERFAHRUNG%
		</td>
		<td align="right"> 
			%ANGRIFF%
		</td>
		<td align="right">
			%VERTEIDIGUNG%
		</td>
		<td align="right">
			%ZIELEN%
		</td>
		<td align="right">
			%GESCHWINDIGKEIT% km/h
		</td>
		<td align="right">
			<input id="%I%" type="checkbox" name="ID[]" value="%CHASSIS%|%ID_PROFIL%|%ID%">
		</td>
	</tr>
	<!-- TemplateEndEditable -->
</table>
<!-- TemplateEndsHere -->