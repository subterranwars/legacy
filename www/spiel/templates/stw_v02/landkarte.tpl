<!-- TemplateBeginsHere -->
<form action="landkarte.php" method="post">
<table width="400">
    <tr>
    	<th colspan="3">
    		:: Landkarte ::
    	</th>
    </tr>
   	<!-- TemplateBeginEditable name="landkarte" -->
    <tr>
   		<td align="center" colspan="3">
   			<table width="150">
   				<tr>
   					<th colspan="3">
   						Längengrad
   					</th>
   					<th colspan="3">
   						Breitengrad
   					</th>
   				</tr>	
   				<tr>
   					<td><input type="submit" name="x1" value="<-"></td>
   					<td><input type="text" size=3  name="x" value="%X%"></td>
   					<td><input type="submit" name="x2" value="->"></td>
   					<td><input type="submit" name="y1" value="<-"></td>
   					<td><input type="text" size=3 name="y" value="%Y%"></td>
   					<td><input type="submit" name="y2" value="->"></td>
   				</tr>
   			</table>
   		</td>
   	</tr>
   	<!-- TemplateEndEditable -->
   	<tr>
   		<th>Kolonie:</th>
   		<th>Name:</th>
   		<th>Pkt:</th>
   	</tr>
   	<!-- TemplateBeginEditable name="landkarte_detail_oben" -->
   	<tr>
   		<th>%KOLONIE%</th>
		<td>%NAME%</td>
   		<td>%PKT%</td>
   	</tr>
   	<!-- TemplateEndEditable -->
   	<tr>
   		<td colspan="3">
   			<hr>
   		</td>
   	</tr>
   	<!-- TemplateBeginEditable name="landkarte_detail_unten" -->
	<tr>
   		<th>%KOLONIE%</th>
   		<td>%NAME%</td>
   		<td>%PKT%</td>
   	</tr>
   	<!-- TemplateEndEditable -->
</table>
</from>
<!-- TemplateEndsHere -->