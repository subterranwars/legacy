<!-- TemplateBeginsHere -->
<html>
<head>
<title>Debug-Meldung</title>
<style type="text/css">
	body, table, a
	{
		background: black;
		font-family: 	Arial;
		font-size: 		9pt;
		color:			#FFFFFF;
		scrollbar-DarkShadow-Color: #000000;  
		scrollbar-Track-Color: 		#434C5D;  
		scrollbar-Face-Color: 		#0F254D;  
		scrollbar-Shadow-Color: 	#000000;  
		scrollbar-Highlight-Color: 	#ffffff;  
		scrollbar-3dLight-Color: 	#0F254D;  
		scrollbar-Arrow-Color: 		#ffffff;  
	}
	
	a:link, a:visited, a:active
	{
		background-color: transparent;  	
	}
	
	a:hover
	{
		color: #C50000;  
	   	text-decoration: underline; 
	}
	
	img
	{
		border-width: 0pt;
	}
	
	.error
	{
		background: #600101;
	}
	
	.green
	{
		color: green;
	}
	
	.orange
	{
		color: orange;
	}
	
	.red
	{
		color: red;
	}
	
	.beschreibung
	{
		font-size: 8pt;
	}
	
	.rohstoffe
	{	line-height: 	7pt;
		font-family: 	Verdana;
		font-size: 		7pt;
	}
	
	.irc
	{
		font-size: 8pt;
		color: black;
		background: white;
		font-weight: bold;
	}
	
	.msg_new
	{
		font-weight: bold;
	}
	
	th
	{
		background: #8E8E8E;
	}
	
	.avatar
	{
		border-style: solid;
		border-width: 1pt;
		border-color: #000000;
	}
	
	.blue
	{
		text-align: left;
		color: blue;
	}
	
	.yellow
	{
		color: yellow;
	}
	
	table.overview
	{
		color: white;
		border-color:	white; 
		border-style:	dashed; 
		border-width: 	1pt;
		width: 100%;
	}
</style>
</head>
<body>
<!-- TemplateBeginEditable name="account" -->
<table>
	<tr>
		<th>Debugmeldung</th>
	</tr>
	<tr>
		<td>
			Am <b>%DATUM%</b> um <b>%UHRZEIT%</b> trat ein Fehler auf
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<th colspan="2">Details</th>
				</tr>
				<tr>
					<td>Betroffener Account:</td>
					<td>#%ID_USER%, %USER%</td>
				</tr>
				<tr>
					<td>Fehlerdatum:</td>
					<td>%DATUM% um %UHRZEIT% Uhr</td>
				</tr>
				<tr>
					<td>Start-Zeit:</td>
					<td>%START_ZEIT%</td>
				</tr>
				<tr>
					<td>Endzeit:</td>
					<td>%END_ZEIT%</td>
				</tr>
				<tr>
					<td>Ereignisschleiffe:</td>
					<td>%EREIGNISSE%</td>
				</tr>
				<tr>
					<td>Ereignisse Detail:</td>
					<td>%EREIGNIS_DETAIL%</td>
				</tr>
				<tr>
					<td>Fehler trat auf bei:</td>
					<td>%EREIGNIS_FEHLER%</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<!-- TemplateEndEditable -->

</body>
</html>
<!-- TemplateEndsHere -->