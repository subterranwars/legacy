<!-- TemplateBeginsHere -->
<!-- TemplateBeginEditable name="lost_pw" -->
<form action="lost_pw.php" method="post">
<table width="500">
    <tr>
    	<th colspan="2">
    		:: Passwort vergessen ::
    	</th>
    </tr>
    <tr>
    	<th>&nbsp;</th>
    	<td>
    		Da Sie anscheinend Ihr Passwort vergessen ahben, können Sie sich nun ein Neues zuschicken.<br>
    		Dazu bitte Ihren Loginnamen (<b>Wichtig:</b> Nicht der Nickname<b>!!!</b>) und Ihre Emailadresse, welche
    		im Spiel hinterlegt ist eintragen und kurz darauf sollte eine Email mit dem
    		neuen Passwort bei Ihnen eintreffen.
    	</td>
    </tr>
    <tr>
    	<th>Loginname:</td>
    	<td>
    		<input type="text" name="login" size="30" maxlenth="50">
    		<div class="beschreibung">
    			Geben Sie hier Ihren Loginnamen ein,<br>
    			welchen Sie zum Login ins Spiel benötigen.
    		</div>
    	</td>
    </tr>
    <tr>
    	<th>Email:</th>
    	<td>
    		<input type="text" name="email" size="30" maxlenth="255">
    		<div class="beschreibung">
    			Geben Sie hier Ihre Email-Adresse ein, welche im Spiel hinterlegt ist ein.<br>
    			Sollten Sie diese vergessen haben, kann kein Passwort zugestellt werden.
    		</div>
    	</td>
    </tr>
    <tr>
    	<th colspan="2">
    		%FEHLER%
    		<div class="green">
    			%ERFOLG%
    		</div>
    	</th>
    </tr> 
    <tr>
    	<th colspan="2">
    		<input type="submit" name="send" value="Passwort anfordern">
    	</th>
    </tr>
</table>
</form>
<!-- TemplateEndEditable -->
<!-- TemplateEndsHere -->