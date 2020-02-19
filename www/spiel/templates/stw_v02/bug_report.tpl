<!-- TemplateBeginsHere -->
<!-- TemplateBeginEditable name="bug_report" -->
<form action="bug_report.php" method="post">
<table>	
	<tr>
		<th colspan="2">
			:: Fehler melden ::
		</th>
	</tr>
	<tr>
		<td colspan="2">
			<b>Bevor ihr hier euren BugReport abgebt, schaut bitte
			vorher unter <a href="bugs.php">Bekannte Fehler</a> nach, ob das
			Problem nicht bereits von jmd anders gepostet wurde! Das erleichtert uns
			die Arbeit</b><br><br>
			
			<b>Fehler melden lohnt sich auf jeden Fall, denn für jeden gemeldeten Bug gibt es eine kleine Belohnung!</b>
		</td>
	</tr>
	<tr>
		<td class="green" colspan="2">
			%FEHLER%
		</td>
	</tr>
	<tr>
		<th colspan="2">
			Fehler berichten:<br>
			<div class="green">
				%ERFOLG%
			</div>
		</th>
	</tr>		
	<tr>
		<th>Titel:</th>
		<td>
			<input type="text" size="30" maxlength="50" value="%TITEL%" name="titel">
			<div class="beschreibung">
				Gib deiner Fehlerbeschreibung einen aussagekräftigen Titel, damit jeder sofort weiss
				wodrum es sich handelt
			</div>
		</td>
	</tr>
	<tr>
		<th>Beschreibung:</th>
		<td>
			<textarea rows="10" cols="45" name="beschreibung">%BESCHREIBUNG%</textarea>
			<div class="beschreibung">
				Beschreibe deinen Fehler ausgiebig. Gib Daten zum verwendeten
				Browser, Betriebssystem und beschreibe deine Tätigkeit wo der Fehler aufgetreten ist.
			</div>
		</td>
	</tr>
	<tr>
		<th colspan="2">
			<input type="submit" name="report" value="Fehler berichten">
		</th>
	</tr>
</table>
<!-- TemplateEndEditable -->
<!-- TemplateEndsHere -->