/* EasyCODE V6.5
 */
/* EasyCODE ( 1
interface_kaserne.php */
//Template laden
$tpl_default = new TEMPLATE("templates/spiel.tpl");
/* EasyCODE - */
//Datenbankverbindung herstellen
$db = new DATENBANK();
/* EasyCODE - */
//Fehlerobjekt erzeugen
$fehler = new FEHLER(&db);
if( $config['Status'] == 0 )
{
    if( $_SESION['USER']->getEingeloggt() == true )
    {
/* EasyCODE < */
        else

/* EasyCODE > */
        switch( $_GET['action'] )
        {
        case 'finish' :
            //Chasis auf korrektheit überprüfen
            if( isset($_SESSION['Chasis'] )
            {
                for( $i = 0; $i < count($_POST['ID']); $i++ )
                {
                    $Teile[0] = $_POST['ID'][$i];
                    $Teile[1] = $_POST['Anzahl'][$i];
                    /* EasyCODE - */
                    $kaserne = new KASERNE(&$db, &$_SESSION['USER']);
                    $error = $kaserne->createProfil($_POST['Profil_Bezeichung'], $Teile);
                }
            }
            else 
            {
                //Kein Chasis und keine Teile gewählt
                //$fehler->meldung( SPACER );
            }
            break;
        case 'neu2' :
            //Chasis ist ausgewählt worden $_POST['ID_Chasis']
            if( isset(_$POST['ID_Chasis']) || isset($_SESSION['Chasis']) )
            {
                if( !isset($_SESSION['Chasis']) )
                {
                    //Chasis - Daten laden
                    $chasis = new TEILECHASIS(&$db);
                    $chasis->loadTeile($_POST['ID_Chasis']);
                    //Chassis in Session speichern
                    $_SESSION['Chasis'] = serialize($chasis);
                }
                for( $_SESSION['USER']->getTeile() as $x )
                {
                    if( $x->getTeileID() == $_POST['ID_Chasis'] )
                    {
                        $_SESSION['Chasis'] 		= $x;
                    }
                    else 
                    {
                        // User darf das Chasis nich bauen :/
                        //$fehler->meldung( SPACER );
                    }
                }
                //Teile vorm Formular in Session schreiben
                for( $i = 0; $i < count($_POST['ID']); $i++ )
                {
                    //Bezeichnung der Category holen
                    $db->query("SELECT ID_Category FROM t_teile WHERE ID_Teile = $_POST['ID'][$i];");
                    $ID_Cat = $db->fetch_result(0);
                    /* EasyCODE - */
                    $db->query("SELECT Bezeichnung FROM t_category WHERE ID_Category = $ID_Cat;");
                    $bez = $db->fetch_result(0);
                    /* EasyCODE - */
                    //Objekt erzeugen
                    $bez = "TEILE".$bez;
                    $Teile[0][$i] = new $bez(&$db);				//Teileobjekt erzeugen
                    $Teile[0][$i]->loadTeile($_POST['ID'][$i]);		//Teile details laden
                    $Teile[1][$i] = $_POST['Anzahl'][$i];			//Anzahl der Teile setzen
                }
                $tpl_build_detail = new TEMPLATE("templates/produktion_profil_detail.tpl");
                $daten_build_detail = array("CHASIS"=>"", "MASSE"=>"", "ENERGIE"=>"", "MASSEVERBRAUCH"=>"", "ENERGIEVERBRAUCH"=>"", "CONTENT"=>"");
                /* EasyCODE - */
                //Teile welche User hat und welche ins Chasis passen selektieren
                for( $_SESSION['USER']->getTeile as $x )
                {
                    if( $x->getMasseverbrauch < $_SESSION['Chasis']->getMasse() AND $x->getEnergieverbrauch < $_SESSION['Chasis']->getEnergie() )
                    {
                        //Teile die schon eingebaut wurden berücksichtigen
                        for( $i = 0; $i < count($Teile[0]); $i++ )
                        {
                            if( $Teile[0][$i]->getTeileID() == $x->getTeileID() )
                            {
                                //Energieverbrauch und Energieverbrauch berechnen
                                $Energieverbrauch 					= $Teile[0][$i]->getEnergieverbrauch();
                                $Masseverbrauch 					= $Teile[0][$i]->getMasseverbrauch();
                                $Anzahl							= $Teile[1][$i];
                                $_SESSION['Verbrauch']['Energie'] 	+= $Energieverbrauch * $Anzahl;
                                $_SESSION['Verbrauch']['Masse']	 	+= $Masseverbrauch * $Anzahl;
                            }
                        }
                        switch(  $x->getCategoryID() )
                        {
                        case 2 :
                            //Antrieb
                            /* EasyCODE - */
                            $tpl_build_detail_content = new TEMPLATE("templates/produktion_profil_detail_antrieb.tpl");
                            $daten_build_detail_content = array("GESCHWINDIGKEIT"=>"".$x->getGeschwindigkeit()."", "ANZAHL"=>"");
                            break;
                        case 3 :
                            //Waffen
                            /* EasyCODE - */
                            $tpl_build_detail_content = new TEMPLATE("templates/produktion_profil_detail_waffen.tpl");
                            $daten_build_detail_content = array("ZIELEN"=>"".$x->getZielen()."", "ANGRIFFSWERT"=>"".$x->getAngriffswert()."", "ANZAHL"=>"");
                            break;
                        case 4 :
                            //Panzerung
                            /* EasyCODE - */
                            $tpl_build_detail_content = new TEMPLATE("templates/produktion_profil_detail_panzerung.tpl");
                            $daten_build_detail_content = array("VERTEIDIGUNG"=>"".$x->getVerteidigung()."", "ANZAHL"=>"");
                            break;
                        case 5 :
                            //Munition
                            /* EasyCODE - */
                            $tpl_build_detail_content = new TEMPLATE("templates/produktion_profil_detail_munition.tpl");
                            $daten_build_detail_content = array("ANGRIFFSWERT"=>"".$x->getAngriffswert()."", "ANZAHL"=>"");
                            break;
                        case 6 :
                            //Ladebuchten
                            /* EasyCODE - */
                            $tpl_build_detail_content = new TEMPLATE("templates/produktion_profil_detail_ladebuchten.tpl");
                            $daten_build_detail_content = array("LADEKAPAZITÄT"=>"".$x->getLadekapazität()."","ANZAHL"=>"");
                            break;
                        //Anzahl setzen
                        $daten_build_detail_content['ANZAHL'] =  $Anzahl;
                        $Anzahl = 0;	//damit Anzahl, wenn Sie nicht explizit gesetzt wird 0 ist !
                        }
                        //Templatedaten laden und ersetzen
                        $tpl_build_detail_content->ersetzen($daten_build_detail_content);
                        $daten_content .= $tpl_build_detail_content->getTemplate();
                    }
                }
                //Chasis-Daten
                $daten_build_detail['CHASIS'] 			= $_SESSION['Chasis']->getBezeichnung();
                $daten_build_detail['MASSE'] 			= $_SESSION['Chasis']->getMasse();
                $daten_build_detail['ENERGIE'] 			= $_SESSION['Chasis']->getEnergie();
                $daten_build_detail['ENERGIEVERBRAUCH'] 	= $_SESSION['Verbrauch']['Energie'];
                $daten_build_detail['MASSEVERBRAUCH'] 	= $_SESSION['Verbrauch']['Masse'];
                /* EasyCODE - */
                //Template ausgeben
                $tpl_build_detail->ersetzen($daten_content);
                $daten_default['CONTENT'] = $tpl_build_detail->getTemplate();
                $tpl_default->ersetzen($daten_default);
                $tpl_default->printTemplate();
            }
            else 
            {
                //Fehlermeldung, kein Chasis ausgewählt
                //$fehler->meldung( SPACER );
            }
            break;
        case 'neu' :
            //Chasis selektieren
            $ID_Category = 1;	//ID vom Chasis
            /* EasyCODE - */
            //Template laden
            $tpl_build = new TEMPLATES("produktion_profil.tpl");
            $daten_build = array("CONTENT"=>"");
            for( $_SESSION['USER']->getTeile() as $x )
            {
                if( $x->getCategoryID() == $ID_Category )
                {
                    $tpl_build_chasis	 = new TEMPLATES("produktion_profil_chasis.tpl");
                    $daten_build_chasis= array("BEZEICHNUNG"=>"", "MASSE"=>"", "ENERGIE"=>"");
                    /* EasyCODE - */
                    $daten_build_chasis['BEZEICHNUNG'] 	= $x->getBezeichnung();
                    $daten_build_chasis['MASSE'] 		= $x->getMasse();
                    $daten_build_chasis['ENERGIE']		= $x->getEnergie();
                    $daten_build_chasis['WENDIGKEIT']	= $x->getWendigkeit();
                    /* EasyCODE - */
                    $tpl_build_chasis->ersetzen($daten_build_chasis());
                    $daten_build .= $tpl_build_chasis->getTemplate();
                }
            }
            //Template ausgeben
            $tpl_build->ersetzen($daten_build);
            $daten_default['CONTENT'] = $tpl_build->getTemplate();
            $tpl_default->ersetzen($daten_default);
            $tpl_default->printTemplate();
            break;
        default :
            //Template laden
            $tpl_default_content = new TEMPLATE("templates/produktion.tpl");
            $daten_default_content = array("PRODUKTION"=>"");
            /* EasyCODE - */
            //Alle Bauaufträge selektieren
            $db->query("SELECT * FROM t_userbauteinheit WHERE ID_User = ".$_SESSION['USER']->getUserID()." ORDER BY Dauer ASC;");
            while( $row = $db->fetch_array() )
            {
                if( time() - $row['FinishTime'] <= 0 )
                {
                    //Fertig stellen
                    $kaserne = new KASERNE(&$db);
                    $kaserne->finishBau($row['ID_Profil'];
                }
                else 
                {
                    $tpl_detail = new TEMPLATE("templates/produktion_build.tpl");
                    $daten_detail = array("PROFIL"=>"", "TIME_LEFT"=>"");
                    /* EasyCODE - */
                    $db->query("SELECT Bezeichnung FROM t_profil WHERE ID_Profil = ".$row['ID_Profil'].";");
                    $profil_bez = $db->fetch_result(0);
                    /* EasyCODE - */
                    $daten_detail['PROFIL'] 		= $profil_bez;
                    $daten_detail['TIME_LEFT'] 	= time() - $row['FinishTime'];
                    /* EasyCODE - */
                    $tpl_detail->ersetzen($daten_detail);
                    $daten_default_content['PRODUKTION'] .= $tpl_detail->getTemplate();
                }
            }
            //Template ausgeben !
            $tpl_default_content->ersetzen($daten_default_content);
            $daten_content['CONTENT'] = $tpl_default_content->getTemplate();
            /* EasyCODE - */
            $tpl_default->ersetzen($daten_content);
            $tpl_default->printTemplate();
        }
    }
    else 
    {
        $fehler->meldung(110);
    }
}
else 
{
    $fehler->meldung(4);
}
/* EasyCODE ) */
