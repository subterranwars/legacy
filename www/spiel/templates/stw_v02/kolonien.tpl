<!-- TemplateBeginsHere -->
<form action="kolonien.php" method="post">
<table>
    <tr>
    	<th colspan="4">
    		:: Kolonien ::
    	</th>
    </tr>
    <tr>
    	<td colspan="4">
    		Hier haben Sie eine Übersicht über Ihre vorhandenen Kolonien.
    		Sollten Sie später zu einem mächtigen Herrscher aufgestiegen sein, werden
    		mit Sicherheit mehrere Kolonien Ihr Eigen sein. Deshalb ist es hier auch möglich eine
    		Kolonie zur Hauptkolonie zu ernennen, welche automatisch nach dem Login geladen wird
    	</td>
    <tr>
    	<th>Koordinaten:</th>
    	<th>Koloniename:</th>
    	<th>Koloniestatus:</th>
    	<th>Hauptkolonie:</th>
    </tr>
    <!-- TemplateBeginEditable name="kolonien" -->
    <tr>
    	<th>%KOORDINATEN%</th>
    	<td><input type="text" name="koloname[]" value="%BEZEICHNUNG%" size="30" maxlength="50">
			<input type="hidden" name="koloid[]" value="%ID%">
		</td>
    	<td>%STATUS%</td>
    	<td>%HAUPTPLANET%</td>
    </tr>
    <!-- TemplateEndEditable -->
    <tr>
    	<th colspan="4">
    		<input type="submit" name="send" value="Kolonien umbenennen">
    	</th>
    </tr>
</table>
</from>
<!-- TemplateEndsHere -->