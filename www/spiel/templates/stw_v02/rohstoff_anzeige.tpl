<!-- TemplateBeginsHere -->
<table>
    <tr>
    	<td colspan="4">
    		:: Rohstoffe ::
    	</td>
    </tr>
    	<tr>
    		<th>Rohstoff:</th>
    		<th>Einkommen:</th>
    		<th>Verbrauch:</th>
    		<th>Gesamt:</th>
    	</tr>
    <!-- TemplateBeginEditable name="rohstoff_anzeige" -->
    <tr>
    	<td>%NAME%:</td>
    	<td class="%CLASS_PROD%">%PRODUKTION%</td>
    	<td class="%CLASS_VERB%">%VERBRAUCH%</td>
    	<td class="%CLASS_GES%">%GESAMT%</td>
    </tr>
    <!-- TemplateEndEditable -->
    <!-- TemplateBeginEditable name="rohstoff_bevölkerung" -->
    <tr>
    	<th colspan="4">
    		Bevölkerungsdaten:
    	</th>
    </tr>
    <tr>
    	<td>Bevölkerung:</td>
    	<td>%BEVÖLKERUNG%</td>
    </tr>
    <tr>
    	<td>Wachstum:</td>
    	<td>%WACHSTUM% %</td>
    </tr>
    <tr>
    	<td>Wohnplätze:</td>
    	<td>%WOHNPLÄTZE%</td>
    </tr>
    <!-- TemplateEndEditable -->
    <tr>
    	<th colspan="4">
    		Lagerkapazitäten
    	</th>
    </tr>
   	<!-- TemplateBeginEditable name="rohstoff_lager" -->
    <tr>
    	<td colspan="2">
    		Normale Rohstoffe:	
    	</td>
    	<td colspan="2">
    		%NORMALE_ROHSTOFFE%
    	</td>
    </tr>
    <tr>
    	<td colspan="2">
    		Radioaktive Rohstoffe:
    	</td>
    	<td colspan="2">
    		%RADIOAKTIVE_ROHSTOFFE%
    	</td>
    </tr>
    <!-- TemplateEndEditable -->
</table>
<!-- TemplateEndsHere -->