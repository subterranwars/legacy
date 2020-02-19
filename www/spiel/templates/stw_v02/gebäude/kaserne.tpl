<!-- TemplateBeginsHere -->
<!-- TemplateBeginEditable name="kaserne" -->	
<form action="" method="post">
<table>
	<tr>
		<th>
			:: Kaserne ::
		</th>
	</tr>
	<tr>
		<td>
			Hier können Sie Infanteristen ausbilden.
			Dazu müssen Sie allerdings als erstes einen sogenannten "Bauplan" anlegen, der
			die Werte (Angriff, Verteidigung etc.) der auszubildenden Einheit bestimmt.
			Nachdem ein "Bauplan" angelgt wurde, kann mit der Ausbildung begonnen werden
		</td>
	</tr>
	<tr>
		<th>
			Baupläne:
		</th>
	</tr>
	<tr>
		<td>
			%FEHLER%
		</td>
	</tr>
	<tr>
		<td>
			%BAUPLÄNE%
		</td>
	</tr>
	<tr>
		<th>
			<a href="bauplan_erstellen_select.php">Neuen Bauplan erstellen</a>
		</th>
	</tr>
	<tr>
		<th>
			Auszubildene Einheiten:
		</th>
	</tr>
	<tr>
		<td>
			%AUSBILDUNG%
		</td>
		<script language=javascript>
		anzahl=%ANZAHL%;
		CountDownEinheiten(anzahl);
		</script>
	</tr>
</table>
</form>
<!-- TemplateEndEditable -->
<!-- TemplateEndsHere -->