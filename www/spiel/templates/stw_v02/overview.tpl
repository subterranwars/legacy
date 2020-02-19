<!-- TemplateBeginsHere -->
<table width="100%">
	<!-- TemplateBeginEditable name="overview" -->
	%NEW_EREIGNISSE%
	%NEW_MSGS%
	%ENERGIE%
	<tr>
		<th colspan="3">
			%KOLONIENAME% (%KOORDINATEN%)
		</th>
	</tr>
	<tr>
		<td valign="top" align="center">
			%OVERVIEW_DETAIL%<br>
			%MISSION_DETAIL%
		</td>
		<td class="beschreibung" align="center" width="220">
			<b>%RASSE%</b><br>
			%BILD%<br>
			Verbrauchte Skillpoints: %SKILLPOINTS_USED%<br>
			Verbleibende Skillpoints:%SKILLPOINTS_LEFT%<br>
			<a href="general.php">--> Skillpoints setzen <--</a>
		</td>
	</tr>
	<script language=javascript>
		anzahl=%ANZAHL%;
		CountDown(anzahl);
	</script>
	<!-- TemplateEndEditable -->
</table>
<!-- TemplateEndsHere -->