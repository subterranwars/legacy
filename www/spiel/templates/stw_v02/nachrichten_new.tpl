<!-- TemplateBeginsHere -->
<!-- TemplateBeginEditable name="nachrichten_new" -->
<html>
<head>
<title>:: www.subterranwar.de ::</title>
<link rel="STYLESHEET" href="templates/stw_v02/style.css" type="text/css">
<script src="templates/stw_v02/scripte.js" type="text/javascript"></script>
</head>
<body background="templates/stw_v02/images/bg.gif">
<form action="nachrichten_new.php" method="post">
<center>
	<table>
		<tr>
			<th colspan="3">
				:: Neue Nachricht verfassen ::
			</th>
		</tr>
		<tr>
			<td colspan="3">%FEHLER%</th>
		</tr>
		<tr>
			<th>Empfänger:</th>
			<td><input type="text" name="empfaenger" size="30" maxlength="50" value="%EMPFAENGER%"></td>
			<th rowspan="3">
				%SMILEYS%
			</th>
		</tr>
		<tr>
			<th>Beteff:</th>
			<td><input type="text" name="betreff" size="30" maxlenght="50" value="%BETREFF%"></td>
		</tr>
		<tr>
			<th>Inhalt:</th>
			<td><textarea cols="32" rows="18" name="inhalt">%INHALT%</textarea></td>
		</tr>
		<tr>
			<th colspan="3">
				<input type="submit" value="abschicken" name="send">
			</th>
		</tr>
	</table>
	<a href="#" onClick="PopUpClose()">Fenster schliessen</a>
	</form>
</center>
</body>
</html>
<!-- TemplateEndEditable -->
<!-- TemplateEndsHere -->