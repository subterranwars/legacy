<!-- TemplateBeginsHere -->
<!-- TemplateBeginEditable name="register" -->
<form action="register.php" method="post">
<table>
	<tr>
		<th colspan="2">
			:: Registrieren ::
		</th>
	</tr>
	<tr>
		<th align="left">
			Nickname:
		</th>
		<td>
			<input type="text" name="nickname" value="%NICKNAME%">
			<div class="beschreibung">
				Welchen Namen wollen Sie im Spiel tragen?<br>
				Kann später nicht geändert werden!
			</div>
		</td>
	</tr>
	<tr>
		<th align="left">
			Loginname:
		</th>
		<td>
			<input type="text" name="loginname" value="%LOGINNAME%">
			<div class="beschreibung">
				Mit diesem Namen müssen Sie sich ins Spiel einloggen.<br>
				Der gewählte Name sollte aus Sicherheitsgründen vom Nickname abweichen.
				Es ist später möglich seinen Loginnamen zu ändern.
			</div>
		</td>
	</tr>
	<tr>
		<th align="left">
			Email:
		</th>
		<td>
			<input type="text" name="email" value="%EMAIL%">
			<div class="beschreibung">
				Ihre Emailadresse. Die Adresse muss gültig sein, da Sie einen
				Freischaltungscode zugeschickt bekommen
			</div>
		</td>
	</tr>
	<tr>
		<th align="left">
			Passwort:
		</th>
		<td>
			<input type="password" name="password">
			<div class="beschreibung">
				Geben Sie ein Passwort ein, welches Sie zum Login
				wünschen.
			</div>
		</td>
	</tr>
	<tr>
		<th align="left">
			Passwort*
		</th>
		<td>
			<input type="password" name="password2">
			<div class="beschreibung">
				Wiederholen Sie die Eingabe von oben.
			</div>
		</td>
	</tr>
	<tr>
		<th align="left">
			Rasse:
		</th>
		<td>
			<select name="rasse" size="2">
				<option value="1" %SEL1%>Terraner</option>
				<option value="2" %SEL2%>SubTerraner</option>
			</select>
			<div class="beschreibung">
				Welche Rasse wollen Sie im Spiel repräsentieren?
			</div>
		</td>
	</tr>
	<tr>
		<th align="left">
			Erklärung:	
		</th>
		<td>
			<input type="checkbox" name="agb" %AGB%> Ich akzeptieren die <a href="#" onClick="PopUp('nutzungsbedingungen.php', 400, 400)">Nutzungsbedingung</a>.
			<div class="beschreibung">
				Um an SubterranWars teilnehmen zu können müssen die
				Nutzungsbedingungen anerkannt werden.
			</div>	
		</td>
	</tr>
	<tr>
		<td colspan="2">
			%FEHLER%
		</td>
	</tr>
	<tr>
		<th colspan="2">
			<input type="submit" name="reg" value="registrieren">
		</th>
	</tr>
</table>
<!-- TemplateEndEditable -->
<!-- TemplateEndsHere -->