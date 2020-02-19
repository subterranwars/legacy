<!-- TemplateBeginsHere -->
<!-- TemplateBeginEditable name="user_info" -->
<html>
<head>
<title>%NICKNAME%</title>
<link rel="STYLESHEET" href="templates/stw_v02/style.css" type="text/css">
<script src="templates/stw_v02/scripte.js" type="text/javascript"></script>
</head>
<body background="templates/stw_v02/images/bg.gif">
<table>
	<tr>
		<th colspan="3">
			:: Spielerdetails ::
		</th>
	</tr>
	<tr>
		<th>
			Nickname:
		</th>
		<td>
			%NICKNAME% <a href="#" onClick="PopUp('nachrichten_new.php?empfaenger=%EMPFAENGER%', 550, 550)"><img alt="Spieler kontaktieren" title="Spieler kontaktieren" src="templates/stw_v02/images/nachrichten.png"></a>
		</td>
		<td rowspan="6">
			%AVATAR%
		</td>
	</tr>
	<tr>
		<th>
			Rasse:
		</th>
		<td>
			%RASSE%
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<hr size=1>
		</td>
	</tr>
	<tr>
		<th>
			Gebäudepunkte:
		</th>
		<td>
			%GEB_PKT%
		</td>
	</tr>
	<tr>
		<th>
			Forschungspunkte:
		</th>
		<td>
			%FORSCHUNGS_PKT%
		</td>
	</tr>
	<tr>
		<th>
			gesamt:
		</th>
		<td>
			%GESAMT_PKT%
		</td>
	</tr>
	<tr>
		<th colspan=3>
			Kolonien
		</th>
	</tr>
	<!-- TemplateEndEditable -->
	<!-- TemplateBeginEditable name="user_info_kolo" -->
	<tr>
		<td class="%CLASS%">
			%KOLO_NAME%
		</td>
		<td class="%CLASS%">
			%KOLO_KOORDS%
		</td>
		<td class="%CLASS%">
			%KOLO_PKT% Pkt.
		</td>
	</tr>
	<!-- TemplateEndEditable -->
</table>
<center>
	[ <a href="#" onClick="PopUpClose()">Fenster schliessen</a> ]
</center>
</body>
</html>
<!-- TemplateEndsHere -->