/* EasyCODE V6.5
 */
/* EasyCODE ( 0
rohstoff.php */
//Template laden
$tpl_default = new TEMPLATE("templates/index.tpl");
$daten_default = array("CONTENT"=>"");
/* EasyCODE - */
//Datenbankverbindung herstellen
$db = new DATENBANK();
/* EasyCODE - */
//Fehlerobjekt erzeugen
$fehler = new FEHLER(&db);
if( $config['Status'] == "online" )
{
    if( $_SESSION['USER']->getEingeloggt() )
    {
/* EasyCODE = */
        switch( $_GET['action'] )
        {
        case 'edit_Laster' :
            //Anzahl der Vorkommen welche verändert werden sollen editieren
            $anzahl = count($_POST['ID']);
            for( $i = 0; $i < $anzahl; $i++ )
            {
/* EasyCODE < */
                $vorkommen = new VORKOMMEN(&$db, &$fehler);
                $vorkommen->loadVorkommen($_POST['ID']['$i']);
                $vorkommen->setAnzahlLaster($_POST['anzahl']['$i'])

/* EasyCODE > */
            }
            break;
        case 'search' :
            $geb = new ROHSTOFF();
            $geb->searchVorkommen($_POST['ID_Res'], $_POST['Dauer'], $_SESSION['USER']->getUserID());
            break;
        default :
            //aktuelle Vorkommen laden
            $db->query("SELECT ID_Vorkommen FROM t_vorkommen WHERE ID_User = ".$_SESSION['USER']->getUserID().";"); 
            $counter = 0;
            while( $row = $db->fetch_array() )
            {
                $vorkommen[$counter] = new VORKOMMEN(&$db, &$fehler);
                $vorkommen[$counter]->loadVorkommen($row['ID_Vorkommen']);
            }
            $tpl_vorkommen = new TEMPLATE("templates/vorkommen.tpl");
            $daten_vorkommen = array("VORKOMMEN"=>"");
            for( $vorkommen as $x )
            {
                $tpl_vorkommen_detail = new TEMPLATE("templates/vorkommen_detail.tpl");
                $daten_vorkommen_detail = array("GRÖßE"=>"", "RESLEFT"=>"", "ROHSTOFF"=>"", "ANZAHLLASTER"=>"");
                /* EasyCODE - */
                $daten_vorkommen_detail['GRÖßE'] 			= $x->getSize();
                $daten_vorkommen_detail['RESLEFT']			= $x->getResLeft();
                $daten_vorkommen_detail['ROHSTOFF']		= $x->getRohstoffName();
                $daten_vorkommen_detail['ANZAHLLASTER']	= $x->GetAnzahlLaster();
                /* EasyCODE - */
                $tpl_vorkommen_detail->ersetzen($daten_vorkommen_detail);
                $daten_vorkommen['VORKOMMEN'] .= $tpl_vorkommen_detail->getTemplate();
            }
            //Template ausgeben
            $tpl_vorkommen->ersetzen($daten_vorkommen);
            $daten_default['CONTENT'] = $tpl_vorkommen->getTemplate();
            
            $tpl_default->ersetzen($daten_default);
            $tpl_default->printTemplate();
        }
    }
    else 
    {
/* EasyCODE < */
        $fehler->meldung(110)

/* EasyCODE > */
    }
}
else 
{
/* EasyCODE < */
    $fehler->meldung(004)

/* EasyCODE > */
}
/* EasyCODE ) */
