<!-- TemplateBeginsHere -->
<!-- TemplateBeginEditable name="nachrichten_show" -->
<html>
<head>
<title>:: www.subterranwar.de ::</title>
<link rel="STYLESHEET" href="templates/stw_v02/style.css" type="text/css">
<script src="templates/stw_v02/scripte.js" type="text/javascript"></script>
</head>
<body background="templates/stw_v02/images/bg.gif">
<center>
	<form action="nachrichten_new.php" method="post">
		<table>
			<tr>
				<th>Datum:</th>
				<td>%DATUM%</td>
			</tr>
			<tr>
				<th>%SPALTE%</th>
				<td>%ABSENDER%</td>
			</tr>
			<tr>
				<th>Betreff:</th>
				<td>%BETREFF%</td>
			</tr>
			<tr>
				<th>Inhalt:</th>
				<td>%INHALT%</td>
			</tr>
			<tr>
				<th colspan="3">
					<input type="hidden" name="ID" value="%ID%">
					<input type="hidden" name="absender" VALUE="%ABSENDER%">
					<input type="submit" value="antworten" name="name">
				</th>
			</tr>
		</table>
		<br>[ <a href="#" onClick="PopUpClose()">Fenster schliessen</a> ]
	</form>
</center>
</body>
</html>
<!-- TemplateEndEditable -->
<!-- TemplateEndsHere -->