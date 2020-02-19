<html>
<head>
<title>Kampfbericht</title>
<style type="text/css">
	body, table
	{
		font-family: 		Verdana;
		font-size: 			8pt;
		color:				#FFFFFF;
	}
	
	table.content
	{
		border-color:		#0F254D; 
		border-style:		solid; 
		border-width: 		1pt;
	}
	
	table.rohstoffe
	{
		font-size: 		7pt;
	}
	
	a:link, a:visited, a:active
	{
		color: 				#C50000;
		background-color: 	transparent;  	
	}
	
	a:hover
	{
		color: orange;  
	   	text-decoration: 	underline; 
	}
	
	th
	{
		background-color: 	#0F254D;
	}
	
	table.user_info_winner
	{
		font-size: 			8pt;
		font-family: 		Verdana;
		color: 				#FFFFFF;
		border-width:		1pt;
		border-color:		green;
		border-style:		dashed;
	}
	
	table.user_info_loser
	{
		font-size: 			8pt;
		font-family: 		Verdana;
		color: 				#FFFFFF;
		border-width:		1pt;
		border-color:		red;
		border-style:		dashed;
	}
	
	img.user_info
	{
		border: 			1pt;
		border-color: 		black;
	}
</style>
<script language="JavaScript">
	var visible = true;

	function changeVisibility(name)
	{
		if(visible == true)
		{
			document.getElementById(name).style.display="block";
			visible = false;
		}
		else
		{
			document.getElementById(name).style.display="none";
			visible = true;
		}
	}
</script>
</head>
<body background="templates/stw_v02/kampfbericht/bg.gif">
%DATUM%
<center>
	<table class="content" width=700>
		<tr>
			<th colspan=5>Kampfbericht</th>
		</tr>
		<tr>
			<td align=center width=300 colspan=2>
				<table width="100%" height="100%" class="%A_CLASS%">
					<tr>
						<td colspan=2 align="center"><i>Angreifer:</i></td>
					</tr>
					<tr>
						<td colspan=2>%A_AVATAR%</td>
					</tr>
					<tr>
						<td><b>Nickname:</b></td>
						<td>%A_NAME%</td>
					</tr>	
					<tr>
						<td><b>Rasse:</b></td>
						<td>%A_RASSE%</td>
					</tr>
					<tr>
						<td><b>Punkte:</b></td>
						<td>%A_PKT%</td>
					</tr>
					<tr>
						<td><b>Kolonie:</b></td>
						<td>%A_KOLONIE% (%A_KOORDS%)</td>
					</tr>
				</table>
			</td>
			<td align=center width=100>
				<b>VS.</b>
			</td>
			<td align=center width=300 colspan=2>
				<table width="100%" height="100%" class="%V_CLASS%">
					<tr>
						<td align="center" colspan=2><i>Verteidiger:</i></td>
					</tr>
					<tr>
						<td colspan=2>%V_AVATAR%</td>
					</tr>
					<tr>
						<td><b>Nickname:</b></td>
						<td>%V_NAME%</td>
					</tr>	
					<tr>
						<td><b>Rasse:</b></td>
						<td>%V_RASSE%</td>
					</tr>
					<tr>
						<td><b>Punkte:</b></td>
						<td>%V_PKT%</td>
					</tr>
					<tr>
						<td><b>Kolonie:</b></td>
						<td>%V_KOLONIE% (%V_KOORDS%)</td>
					</tr>
				</table>
			</td>
		</tr>		
		<tr>
			<th colspan=5><i>Gewinner: %WINNER_NAME%</i></th>
		</tr>	
		<tr>
			<td colspan=5 height=5></td>
		</tr>	
		<tr>
			<td width=250><img src="templates/stw_v02/kampfbericht/gesamt.gif" height="15" width="%A_ANZ_W%"></td>
			<td width=50>%A_ANZ%</td>
			<th>Gesamt:</th>
			<td width=250><img src="templates/stw_v02/kampfbericht/gesamt.gif" height="15" width="%V_ANZ_W%"></td>
			<td width=50>%V_ANZ%</td>
		</tr>
		<tr>
			<td colspan=5 height=5></td>
		</tr>	
		<!-- Infanteristen -->
		<!-- Gesamt -->
		<tr>
			<td><img src="templates/stw_v02/kampfbericht/gesamt.gif" height="15" width="%A_ANZ1_W%"></td>
			<td>%A_ANZ1%</td>
			<th rowspan=3>Infanteristen</th>
			<td><img src="templates/stw_v02/kampfbericht/gesamt.gif" height="15" width="%V_ANZ1_W%"></td>
			<td>%V_ANZ1%</td>
		</tr>
		<!-- Verluste -->
		<tr>
			<td><img src="templates/stw_v02/kampfbericht/lost.gif" height="15" width="%A_LOST1_W%"></td>
			<td>%A_LOST1%</td>
			<td><img src="templates/stw_v02/kampfbericht/lost.gif" height="15" width="%V_LOST1_W%"></td>
			<td>%V_LOST1%</td>
		</tr>
		<!-- Verbleibend -->
		<tr>
			<td><img src="templates/stw_v02/kampfbericht/left.gif" height="15" width="%A_LEFT1_W%"></td>
			<td>%A_LEFT1%</td>
			<td><img src="templates/stw_v02/kampfbericht/left.gif" height="15" width="%V_LEFT1_W%"></td>
			<td>%V_LEFT1%</td>
		</tr>
		<tr>
			<td colspan=5 height=5></td>
		</tr>			
		<!-- Fahrzeuge -->
		<!-- Gesamt -->
		<tr>
			<td><img src="templates/stw_v02/kampfbericht/gesamt.gif" height="15" width="%A_ANZ2_W%"></td>
			<td>%A_ANZ2%</td>
			<th rowspan=3>Fahrzeuge</th>
			<td><img src="templates/stw_v02/kampfbericht/gesamt.gif" height="15" width="%V_ANZ2_W%"></td>
			<td>%V_ANZ2%</td>
		</tr>
		<!-- Verluste -->
		<tr>
			<td><img src="templates/stw_v02/kampfbericht/lost.gif" height="15" width="%A_LOST2_W%"></td>
			<td>%A_LOST2%</td>
			<td><img src="templates/stw_v02/kampfbericht/lost.gif" height="15" width="%V_LOST2_W%"></td>
			<td>%V_LOST2%</td>
		</tr>
		<!-- Verbleibend -->
		<tr>
			<td><img src="templates/stw_v02/kampfbericht/left.gif" height="15" width="%A_LEFT2_W%"></td>
			<td>%A_LEFT2%</td>
			<td><img src="templates/stw_v02/kampfbericht/left.gif" height="15" width="%V_LEFT2_W%"></td>
			<td>%V_LEFT2%</td>
		</tr>
		<tr>
			<td colspan=5 height=5></td>
		</tr>	
		<!-- Mechs -->
		<!-- Gesamt -->
		<tr>
			<td><img src="templates/stw_v02/kampfbericht/gesamt.gif" height="15" width="%A_ANZ3_W%"></td>
			<td>%A_ANZ3%</td>
			<th rowspan=3>Mechs</th>
			<td><img src="templates/stw_v02/kampfbericht/gesamt.gif" height="15" width="%V_ANZ3_W%"></td>
			<td>%V_ANZ3%</td>
		</tr>
		<!-- Verluste -->
		<tr>
			<td><img src="templates/stw_v02/kampfbericht/lost.gif" height="15" width="%A_LOST3_W%"></td>
			<td>%A_LOST3%</td>
			<td><img src="templates/stw_v02/kampfbericht/lost.gif" height="15" width="%V_LOST3_W%"></td>
			<td>%V_LOST3%</td>
		</tr>
		<!-- Verbleibend -->
		<tr>
			<td><img src="templates/stw_v02/kampfbericht/left.gif" height="15" width="%A_LEFT3_W%"></td>
			<td>%A_LEFT3%</td>
			<td><img src="templates/stw_v02/kampfbericht/left.gif" height="15" width="%V_LEFT3_W%"></td>
			<td>%V_LEFT3%</td>
		</tr>
		<!-- Legende -->
		<tr>
			<td align=center colspan=5>
				<table class="rohstoffe">
					<tr>
						<th colspan=9>Legende:</th>
					</tr>
					<tr>
						<td><img src="templates/stw_v02/kampfbericht/gesamt.gif" width=15 height=8></td>
						<td>Anzahl gesamt</td>
						<td width=10></td>
						<td><img src="templates/stw_v02/kampfbericht/lost.gif" width=15 height=8></td>
						<td>Anzahl zerstört</td>
						<td width=10></td>
						<td><img src="templates/stw_v02/kampfbericht/left.gif" width=15 height=8></td>
						<td>Anzahl verbleibend</td>
						<td width=10></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th colspan="5">
				<a href="#" onClick="changeVisibility(1)">Kampfdetails anzeigen</a>
			</th>
		</tr>
		<tr>
			<td align=center valign=top colspan=2>
				<table class="rohstoffe">
					<tr>
						<th colspan=5>geplünderte Rohstoffe</th>
					</tr>
					<tr>
						<th>Eisen:</th>
						<td>%RES1%</td>
						<td></td>
						<th>Stein:</th>
						<td>%RES2%</td>
					</tr>
					<tr>
						<th>Nahrung:</th>
						<td>%RES3%</td>
						<td></td>
						<th>Öl:</th>
						<td>%RES4%</td>
					</tr>
					<tr>
						<th>Kunststoff:</th>
						<td>%RES5%</td>
						<td></td>
						<th>Stahl:</th>
						<td>%RES6%</td>
					</tr>
					<tr>
						<th>Titanerz:</th>
						<td>%RES7%</td>
						<td></td>
						<th>Titan:</th>
						<td>%RES8%</td>
					</tr>
					<tr>
						<th>Wasser:</th>
						<td>%RES9%</td>
						<td></td>
						<th>Wasserstoff:</th>
						<td>%RES10%</td>
					</tr>
					<tr>
						<th>Uran:</th>
						<td>%RES11%</td>
						<td></td>
						<th>Plutonium:</th>
						<td>%RES12%</td>
					</tr>
					<tr>
						<th>Diamant:</th>
						<td>%RES13%</td>
						<td></td>
						<th>Gold:</th>
						<td>%RES14%</td>
					</tr>
				</table>
			</td>
			<td valign=top align=center colspan="3">
				<div id=1 style="display:none">
					<br>
					<table class="rohstoffe">
						<tr>
							<td></td>
							<th colspan=2>Infanterist:</th>
							<th colspan=2>Fahrzeuge:</th>
							<th colspan=2>Mechs:</th>
						</tr>
						%KAMPFBERICHT_DETAIL%
					</table>
					<br>
				</div>
			</td>
		</tr>
	</table>
</center>
</body>
</html>