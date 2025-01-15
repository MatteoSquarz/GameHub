<?php
require_once "templatedbConnection.php";
use DB\DBAccess;

$paginaHTML = file_get_contents('admin.html');

$connection = new DBAccess();
if(!$connection->openDBConnection())
    header("Location: /TecWeb-project/500.php");

session_start();
if(!isset($_SESSION['username']) || !($connection->verifyAdmin($_SESSION['username'])))
{
    $connection->closeDBConnection();
    header("Location: /TecWeb-project/404.php");
    exit();
}
$connection->closeDBConnection();
if(isset($_GET['logout']))
{
    unset($_SESSION['username']);
    header("Location: /TecWeb-project/index.php");
  	exit();
}

$abbonamenti = "";
$listaAbbonamenti = "";
$categorie = "";
$listaCategorie = "";
$piattaforme = "";
$listaPiattaforme = "";

if($connection->openDBConnection())
{
    $abbonamenti = $connection->getListAbbonamenti();
    $categorie = $connection->getListCategorie();
    $piattaforme = $connection->getListPiattaforme();
    $connection->closeDBConnection();


    foreach($abbonamenti as $abbonamento){
        $nome = $abbonamento['nome'];
        $listaAbbonamenti .= "<option value=\"$nome\">$nome</option>";
    }

    foreach($piattaforme as $piattaforma){
        $nome = $piattaforma['nome'];
        $value = $nome;
        $nome = str_replace(' ', '-', $nome);
        $listaPiattaforme .= "<div class=\"sceltaPiattaforma\">";
        $listaPiattaforme .= "<input type=\"checkbox\" id=\"$nome\" name=\"$nome\" value=\"Piattaforma$value\" />";
        $listaPiattaforme .= "<label for=\"$nome\"> $value</label>";
        $listaPiattaforme .= "</div>";
    }

    $count = 0;
    foreach($categorie as $categoria){
        if($count % 5 == 0) { //apre un nuovo gruppo di 5 categorie
            $listaCategorie .= "<div class=\"gruppoScelteCategoria\">";
        }
        $nome = $categoria['nome'];
        $value = $nome;
        $nome = str_replace(' ', '-', $nome);
        $listaCategorie .= "<div>";
        $listaCategorie .= "<input type=\"checkbox\" id=\"$nome\" name=\"$nome\" value=\"Categoria$value\" />";
        $listaCategorie .= "<label for=\"$nome\"> $value</label>";
        $listaCategorie .= "</div>";
        if($count % 5 == 4) { //chiude il gruppo di 5 categorie
            $listaCategorie .= "</div>";
        }
        $count++;
    }
    $count--;
    if($count % 5 != 4) { //chiude l'ultimo gruppo di 5 categorie
        $listaCategorie .= "</div>";
    }

}
else
    header("Location: /TecWeb-project/500.php");

$paginaHTML = str_replace('[listaAbbonamenti]', $listaAbbonamenti, $paginaHTML);
$paginaHTML = str_replace('[listaCategorie]', $listaCategorie, $paginaHTML);
$paginaHTML = str_replace('[listaPiattaforme]', $listaPiattaforme, $paginaHTML);

$messaggioErroreOutput = "<div class=\"divForm\"><h2>Risultato</h2><p class=\"itemCentered errorFormAdmin\">Qualcosa è andato storto! Gli errori rilevati sono stati stampati in fondo al form su cui stavi lavorando.</p></div>";

$messaggiInserimento = "";

if (isset($_POST['inserisciVideogioco'])) {
	$piat = array();
    $cat = array();
    foreach ($_POST as $p)
    {
        if (str_contains($p,"Piattaforma"))
        {
            $p = str_replace("Piattaforma","",$p);
            array_push($piat,$p);
        }
        else if(str_contains($p,"Categoria"))
        {
            $p = str_replace("Categoria","",$p);
            array_push($cat,$p);
        }
    }

    $messaggiInserimento .= "<ul class=\"itemCentered errorFormAdmin\">";

    if($connection->openDBConnection())
    {
	    $codice = $connection->pulisciInput($_POST['codice']);
        $titolo = $connection->pulisciInput($_POST['titolo']);
        $dataUscita = $connection->pulisciInput($_POST['data-uscita']);
        $prezzo = $connection->pulisciInput($_POST['prezzo']);
        $casaSviluppatrice = $connection->pulisciInput($_POST['casa-sviluppatrice']);
        $descrizione = $connection->pulisciInput($_POST['descrizione']);
        $connection->closeDBConnection();
    }
    else
        header("Location: /TecWeb-project/500.php");

	if(!preg_match("/^[0-9]{8,8}$/",$codice))
		$messaggiInserimento .= "<li>Il codice contiene solo numeri e deve essere di 8 caratteri</li>";

    if(!preg_match("/^[A-Za-z0-9\ \']{2,20}$/",$titolo))
        $messaggiInserimento .= "<li>Il titolo non può contenere caratteri speciali, almeno 2 caratteri max 20</li>";

    $pegi = $_POST['pegi'];
	
	if(!preg_match("/^([0-9]{1,3})$/",$prezzo))
		$messaggiInserimento .= "<li>Il prezzo deve essere compreso tra 0 e 999</li>";
   
	if(!preg_match("/^[A-Za-z0-9\ \']{2,30}$/",$casaSviluppatrice))
        $messaggiInserimento .= "<li>La casa sviluppatrice contiene solo lettere o numeri, almeno 2 caratteri max 30</li>";

    $img = $_POST['immagine'];
    if($img == "")
        $messaggiInserimento .= "<li>Nessun immagine selezionata</li>";

    $abb = $_POST['abbonamentoMin'];
    
	if(!preg_match("/^[\s\S]{20,1000}$/",$descrizione))
        $messaggiInserimento .= "<li>La descrizione deve essere di almeno 20 caratteri max 1000</li>";

    if(count($piat) == 0)
        $messaggiInserimento .= "<li>Selezionare almeno una piattaforma</li>";

    if(count($cat) == 0)
        $messaggiInserimento .= "<li>Selezionare almeno una categoria</li>";

	$messaggiInserimento .= "</ul>";

	if($messaggiInserimento == "<ul class=\"itemCentered errorFormAdmin\"></ul>"){
		if( $connection->openDBConnection())
        {
			if($connection->getGiocoByCodice($codice) == null)
			{
				$nuovoGioco = $connection->insertGioco($codice,$titolo,$descrizione,$prezzo,$dataUscita,$pegi,$casaSviluppatrice,$img);
                $connection->closeDBConnection();
				if($nuovoGioco)
                {
                    if($connection->openDBConnection())
                    {
                        $connection->insertCategorieGioco($codice, $cat);
                        $connection->insertPiattaformeGioco($codice, $piat);
                        $connection->insertAbbonamentiGioco($codice, $abb);
                        $connection->closeDBConnection();
                    }
                    else
                        header("Location: /TecWeb-project/500.php");
                    $paginaHTML = str_replace('[messaggioOutput]', "<div class=\"divForm\"><h2>Risultato</h2><p class=\"itemCentered confermaOperazioneAdmin\">Inserimento avvenuto con successo</p></div>", $paginaHTML);
                }
			}
			else{
                $messaggiInserimento = "<p class=\"itemCentered errorFormAdmin\">Codice gioco già utilizzato, si prega di usarne un altro</p>";
                $paginaHTML = str_replace('[messaggioOutput]', $messaggioErroreOutput, $paginaHTML);
                $connection->closeDBConnection();
            }			
		}     
        else
            header("Location: /TecWeb-project/500.php");
	} 
    else
        $paginaHTML = str_replace('[messaggioOutput]', $messaggioErroreOutput, $paginaHTML);

}
$paginaHTML = str_replace('[messaggiInserimento]', $messaggiInserimento, $paginaHTML);

$messaggioRimozione = "";
if (isset($_POST['rimuoviVideogioco'])) {

    if($connection->openDBConnection())
    {
	    $codice = $connection->pulisciInput($_POST['codice-rimozione']);
        $connection->closeDBConnection();
    }
    else
        header("Location: /TecWeb-project/500.php");

	if(!preg_match("/^[0-9]{8,8}$/",$codice)){
        $messaggioRimozione .= "<p class=\"itemCentered errorFormAdmin\">Il codice contiene solo numeri e deve essere di 8 caratteri</p>";
        $paginaHTML = str_replace('[messaggioOutput]', $messaggioErroreOutput, $paginaHTML);	
    }

	if($messaggioRimozione == ""){
		if($connection->openDBConnection())
        {
			if($connection->getGiocoByCodice($codice) == null){
                $messaggioRimozione = "<p class=\"itemCentered errorFormAdmin\">Codice gioco non presente</p>";
                $paginaHTML = str_replace('[messaggioOutput]', $messaggioErroreOutput, $paginaHTML);
                $connection->closeDBConnection();
            }
			else
            {
                $rimozione = $connection->rimuoviGioco($codice);
                $connection->closeDBConnection();
				if($rimozione)
                    $paginaHTML = str_replace('[messaggioOutput]', "<div class=\"divForm\"><h2>Risultato</h2><p class=\"itemCentered confermaOperazioneAdmin\">Rimozione avvenuta con successo</p></div>", $paginaHTML);
            }				
		}
        else
            header("Location: /TecWeb-project/500.php");   
	}
}
$paginaHTML = str_replace('[messaggioRimozione]', $messaggioRimozione, $paginaHTML);

$messaggioModifica = "";
if (isset($_POST['modificaAbbonamento'])) {

    if($connection->openDBConnection())
    {
        $prezzo = $connection->pulisciInput($_POST['nuovo-costo']);
        $abb = $connection->pulisciInput($_POST['abbonamento']);
        $connection->closeDBConnection();
    }
    else
        header("Location: /TecWeb-project/500.php");

	if(!preg_match("/^[0-9]{1,3}$/",$prezzo)){
		$messaggioModifica = "<p class=\"itemCentered errorFormAdmin\">Il prezzo deve essere compreso tra 0 e 999</p>";
        $paginaHTML = str_replace('[messaggioOutput]', $messaggioErroreOutput, $paginaHTML);
    }

    if($messaggioModifica == "")
    {
	    if($connectionOK = $connection->openDBConnection())
        {
		    if($connection->modificaPrezzoAbbonamento($abb,$prezzo))
            {
                $paginaHTML = str_replace('[messaggioOutput]', "<div class=\"divForm\"><h2>Risultato</h2><p class=\"itemCentered confermaOperazioneAdmin\">Modifica avvenuta con successo</p></div>", $paginaHTML);
                $connection->closeDBConnection();
            }
            else{
                $messaggioModifica = "<p class=\"itemCentered errorFormAdmin\">Il nuovo prezzo dell'abbonamento è già quello impostato</p>";
                $paginaHTML = str_replace('[messaggioOutput]', $messaggioErroreOutput, $paginaHTML);
                $connection->closeDBConnection();
            }				
	    }
    }       
}
$paginaHTML = str_replace('[messaggioModifica]', $messaggioModifica, $paginaHTML);
$paginaHTML = str_replace('[messaggioOutput]', "", $paginaHTML);
echo $paginaHTML;
?>