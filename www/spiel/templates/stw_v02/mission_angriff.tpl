<!-- TemplateBeginsHere -->
<!-- TemplateBeginEditable name="mission_angriff" -->
<form action="mission.php?action=finish" method="post">
<table>
	<tr>
		<th colspan="2">
			Angriffsparameter
		</th>
	</tr>
	<tr>
		<td>
			Rohstoffpriorität:
			<div class="beschreibung">
				Welche Rohstoffe wollen Sie bevorzugt plündern?<br>
				Achten Sie darauf, dass nicht zwei gleiche Rohstoffe
				bei der Auswahl vorkommen!
			</div>
		</td>
		<td>
			%ROHSTOFFE%
		</td>
	<tr>
	<tr>
		<td>
			Artilleriesupport:
			<div class="beschreibung">
				Wenn sich in Ihrer Flotte Artillerie befindet, können Sie
				von einer befreundeten Stellung aus Artillerieschläge ausführen.
				Um so den Gegner vor einem Angriff enorm zu schwächen.
			</div>
		</td>
		<td>
			%ARTILLERIE%
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="submit" value="weiter" name="m3">
		</td>
	</tr>
</table>
</form>
<!-- TemplateEndEditable -->
<!-- TemplateEndsHere -->