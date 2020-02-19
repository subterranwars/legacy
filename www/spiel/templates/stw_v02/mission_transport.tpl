<!-- TemplateBeginsHere -->
<form action="mission.php?action=finish" method="post">
<table>
	<tr>
		<th colspan="2">
			Rohstofftransport
		</th>
	</tr>
	<!-- TemplateBeginEditable name="mission_transport_detail" -->
	<tr>
		<th>
			%NAME%
		</th>
		<td>
			<input type="text" name="m_t_res[]" value="0" size="15" maxlenght="11">
			<input type="hidden" name="m_t_id[]" value="%ID%">
		</td>
	</tr>
	<!-- TemplateEndEditable -->
	<!-- TemplateBeginEditable name="mission_transport" -->
	<tr>
		<th>Maximale Ladekapazität:</th>
		<td>%MAX_LADEKAPAZITAET%</td>
	</tr>
	<!-- TemplateEndEditable -->
	<tr>
		<td colspan="2">
			<input type="submit" value="weiter" name="m4">
		</td>
	</tr>
</table>
</form>
<!-- TemplateEndsHere -->