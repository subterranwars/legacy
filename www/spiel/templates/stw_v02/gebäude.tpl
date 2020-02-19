<!-- TemplateBeginsHere -->
<table background="green" width="100%">
    <tr>
        <th align="center" colspan="10">
        	:: Gebäudeübersicht ::
        </th>
    </tr>
    <!-- TemplateBeginEditable name="gebäude" -->
    %TOPIC%
    <tr>
    	<th rowspan="2">
    		<a href="%LINK%">%GEBÄUDENAME%</a><br>
    		%STUFE%
    	</th>
    	<td colspan="8">
    		<img src="templates/stw_v02/images/info.png" title="%BESCHREIBUNG%"><br>
    		Bauzeit: %ZEIT%<br>
    		Energieverbrauch: %ENERGIE% GW (n. Level: %NEXT% GW)
    	</td>
    	<th rowspan="2">
    		%TEXT%
    	</th>
    </tr>
	<tr>
		<td class="%CLASS_EISEN%">Eisen:</td>
		<td class="%CLASS_EISEN%">%EISEN%</td>
		<td class="%CLASS_STEIN%">Stein:</td>
		<td class="%CLASS_STEIN%">%STEIN%</td>
		<td class="%CLASS_STAHL%">Stahl:</td>
		<td class="%CLASS_STAHL%">%STAHL%</td>
		<td class="%CLASS_TITAN%">Titan:</td>
		<td class="%CLASS_TITAN%">%TITAN%</td>
	</tr>
    <!-- TemplateEndEditable -->
</table>
</form>
<!-- TemplateEndsHere -->