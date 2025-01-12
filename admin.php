<?php
require_once "templatedbConnection.php";
use DB\DBAccess;

$paginaHTML = file_get_contents('admin.html');

$connection = new DBAccess();
$connectionOK = $connection->openDBConnection();

session_start();
//if(!isset($_SESSION['username']) || !($connection->verifyAdmin($_SESSION['username'])))
//{
//    header("Location: /TecWeb-project/404.php");
//    exit();
//}

if(isset($_GET['logout'])){
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

if(!$connectionOK){
    $abbonamenti = $connection->getListAbbonamenti();
    $categorie = $connection->getListCategorie();
    $piattaforme = $connection->getListPiattaforme();
    //$connection->closeDBConnection();


    foreach($abbonamenti as $abbonamento){
        $nome = $abbonamento['nome'];
        $listaAbbonamenti .= "<option value=\"$nome\">$nome</option>";
    }

    foreach($piattaforme as $piattaforma){
        $nome = $piattaforma['nome'];
        $value = $nome;
        $nome = str_replace(' ', '-', $nome);
        $listaPiattaforme .= "<div class=\"sceltaPiattaforma\">";
        $listaPiattaforme .= "<input type=\"checkbox\" id=\"$nome\" name=\"$nome\" value=\"$value\" />";
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

$paginaHTML = str_replace('[listaAbbonamenti]', $listaAbbonamenti, $paginaHTML);
$paginaHTML = str_replace('[listaCategorie]', $listaCategorie, $paginaHTML);
$paginaHTML = str_replace('[listaPiattaforme]', $listaPiattaforme, $paginaHTML);

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

    $messaggiInserimento .= "<ul>";

	$codice = $connection->pulisciInput($_POST['codice']);
	if(!preg_match("/^[0-9]{8,8}$/",$codice))
		$messaggiInserimento .= "<li>Il codice contiene solo numeri e deve essere di 8 caratteri</li>";

    $titolo = $connection->pulisciInput($_POST['titolo']);
    if(!preg_match("/^[A-Za-z0-9\ \']{2,20}$/",$titolo))
        $messaggiInserimento .= "<li>Il titolo non può contenere caratteri speciali, almeno 2 caratteri max 20</li>";

	$dataUscita = $connection->pulisciInput($_POST['data-uscita']);

    $pegi = $_POST['pegi'];

	$prezzo = $connection->pulisciInput($_POST['prezzo']);
	if(!preg_match("/^([0-9]{1,3})$/",$prezzo))
		$messaggiInserimento .= "<li>Il prezzo è compreso tra 0 e 999</li>";

    $casaSviluppatrice = $connection->pulisciInput($_POST['casa-sviluppatrice']);
	if(!preg_match("/^[A-Za-z0-9\ \']{2,30}$/",$casaSviluppatrice))
        $messaggiInserimento .= "<li>La casa sviluppatrice contiene solo lettere o numeri, almeno 2 caratteri max 30</li>";

    $img = $_POST['immagine'];
    if($img == "")
        $messaggiInserimento .= "<li>Nessun immagine selezionata</li>";

    $abb = $_POST['abbonamentoMin'];

    $descrizione = $connection->pulisciInput($_POST['descrizione']);
	if(!preg_match("/^[\s\S]{20,1000}$/",$descrizione))
        $messaggiInserimento .= "<li>La descrizione deve essere di almeno 20 caratteri max 1000</li>";

    if(count($piat) == 0)
        $messaggiInserimento .= "<li>Selezionare almeno una piattaforma</li>";

    if(count($cat) == 0)
        $messaggiInserimento .= "<li>Selezionare almeno una categoria</li>";

	$messaggiInserimento .= "</ul>";

	if($messaggiInserimento == "<ul></ul>"){
		if($connectionOK == NULL)
		{
			if($connection->getGiocoByCodice($codice) == null)
			{
				$nuovoGioco = $connection->insertGioco($codice,$titolo,$descrizione,$prezzo,$dataUscita,$pegi,$casaSviluppatrice,$img);
				if($nuovoGioco)
                {
                    $connection->insertCategorieGioco($codice, $cat);
                    $connection->insertPiattaformeGioco($codice, $piat);
                    $connection->insertAbbonamentiGioco($codice, $abb);
                    $messaggiInserimento = "<p>Inserimento avvenuto con successo</p>";
                }
			}
			else
				$messaggiInserimento = "<p>Codice gioco già utilizzato, si prega di usarne un altro</p>";				
		}        
	}
}
$paginaHTML = str_replace('[messaggiInserimento]', $messaggiInserimento, $paginaHTML);

$messaggioRimozione = "";
if (isset($_POST['rimuoviVideogioco'])) {
	$messaggioRimozione .= "<ul>";

	$codice = $connection->pulisciInput($_POST['codice-rimozione']);
	if(!preg_match("/^[0-9]{8,8}$/",$codice))
		$messaggioRimozione .= "<li>Il codice contiene solo numeri e deve essere di 8 caratteri</li>";

	$messaggioRimozione .= "</ul>";

	if($messaggioRimozione == "<ul></ul>"){
		if($connectionOK == NULL)
		{
			if($connection->getGiocoByCodice($codice) == null)
                $messaggioRimozione = "<p>Codice gioco non presente</p>";
			else
            {
                $rimozione = $connection->rimuoviGioco($codice);
				if($rimozione)
                    $messaggioRimozione = "<p>Rimozione avvenuta con successo</p>";
            }				
		}        
	}
}
$paginaHTML = str_replace('[messaggioRimozione]', $messaggioRimozione, $paginaHTML);

$messaggioModifica = "";
if (isset($_POST['modificaAbbonamento'])) {
	$prezzo = $connection->pulisciInput($_POST['nuovo-costo']);
	if(!preg_match("/^[0-9]{1,3}$/",$prezzo))
		$messaggioModifica = "<p>Il prezzo è compreso tra 0 e 999</p>";

	$abb = $connection->pulisciInput($_POST['abbonamento']);

    if($messaggioModifica == "")
    {
	    if($connectionOK == NULL)
	    {
		    if($connection->modificaPrezzoAbbonamento($abb,$prezzo))
		    {
                $messaggioModifica = "<p>Modifica avvenuta con successo</p>";
		    }
		    else
			    $messaggioModifica = "<p>Errore</p>";				
	    }
    }       
}
$paginaHTML = str_replace('[messaggioModifica]', $messaggioModifica, $paginaHTML);

echo $paginaHTML;
?>