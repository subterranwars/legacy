<!-- TemplateBeginsHere -->
<!-- TemplateBeginEditable name="mission_parameter" -->
<form action="mission.php?action=koordinaten" method="post">
<table>
	<tr>
		<th colspan="2">Misssionstyp</th>
	</tr>
	<tr>
		<th><input type="radio" value="1" name="typ"></th>
		<td>
			Angriff
			<div class="beschreibung">
				Mit dieser Option ist es möglich einen Feind anzugreifen
			</div>
		</td>
	</tr>
	<tr>
		<th><input type="radio" value="2" name="typ" disabled></th>
		<td>
			Verteidigung
			<div class="beschreibung">
				Wird einer Ihrer Verbündeten angegriffen?
				<br>mit dieser Option können Sie ihm zu Hilfe eilen
			</div>
		</td>
	</tr>
	<tr>
		<th><input type="radio" value="6" name="typ"></th>
		<td>
			Rohstoffe transportieren
			<div class="beschreibung">
				Wollen Sie einem verbündeten Rohstoffe schicken, oder müssen
				einem Feind Tribut zahlen? Dann bitte hier entlang.
			</div>
		</td>
	</tr>
	<tr>
		<th><input type="radio" value="3" name="typ"></th>
		<td>
			Truppe verlegen
			<div class="beschreibung">
				Verlegen Sie Ihre Truppe auf eine andere Kolonie.
			</div>
		</td>
	</tr>
	<tr>
		<th><input type="radio" value="4" name="typ"></th>
		<td>
			Truppe übergeben
			<div class="beschreibung">
				Übergeben Sie Ihre Truppe an einen Verbündeten.
			</div>
		</td>
	</tr>	
	<tr>
		<th><input type="radio" value="5" name="typ" disabled></th>
		<td>
			Kolonisieren
			<div class="beschreibung">
				Expandieren Sie und gründen eine neue Kolonie.
			</div>
		</td>
	</tr>	
	<tr>
		<th colspan="2">
			<input type="submit" name="m1" value="weiter">
		</th>
	</tr>
</table>
</form>
<!-- TemplateEndEditable -->
<!-- TemplateEndsHere -->