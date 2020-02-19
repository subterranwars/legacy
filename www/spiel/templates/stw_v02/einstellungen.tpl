<!-- TemplateBeginsHere -->
<!-- TemplateBeginEditable name="einstellungen" -->
<form action="einstellungen.php" method="post" enctype="multipart/form-data">
<table width="100%">
    <tr>
        <th colspan="2">
           :: Einstellungen ändern ::
        </th>
    </tr>
    <tr>
        <th width="100">Loginname:</th>
        <td>
			<input type="text" name="loginname" value="%LOGINNAME%" maxlength="50" size="30">
		</td>
    </tr>
    <tr>
        <th>Nickname:</th>
        <td>
        	%NICKNAME%
        </td>
    </tr>
    <tr>
        <th>Email:</th>
        <td>
			<input type="text" name="email" value="%EMAIL%" maxlength="255" size="30">
        </td>
    </tr>
    <tr>
    	<th>Registriert seit:</th>
    	<td>
    		%REG_DATE%
    	</td>
    </tr>
    <tr>
    	<th>Skin:</th>
    	<td>
    		%SKIN%
    	</td>
    </tr>
    <tr>
        <th valign="top">
        	Avatar:<br>
        	%AVATAR%
        </th>
        <td>            
        	<input type="file" name="avatar" size="30">
        </td>
    </tr>
    <tr>
        <th colspan="2">
            <a href="pw_change.php">Passwort ändern</a>       
		</th>
    </tr>
    <tr>
        <th width="966" colspan="2">
        	<input type="submit" name="senden" value="Einstellungen Speichern">
		</th>
    </tr>
</table>
</form>
<!-- TemplateEndEditable -->
<!-- TemplateEndsHere -->