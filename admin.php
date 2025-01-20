<?php
require_once "templatedbConnection.php";
use DB\DBAccess;

function pulisciInput($value){
    $value = trim($value);
    $value = strip_tags($value);
    $value = htmlentities($value);
    return $value;
}

$paginaHTML = file_get_contents('template/admin.html');

$connection = new DBAccess();
$connectionOK = false;

session_start();
try{
    $connectionOK = $connection->openDBConnection();
    if($connectionOK){
        if(!isset($_SESSION['username']) || !($connection->verifyAdmin($_SESSION['username'])))   //se non è loggato come admin
            header("Location: 404.php");
    }
    else
        header("Location: 500.php");
}
catch(mysqli_sql_exception $e){   //se c'è un errore a livello database
    header("Location: 500.php");
}
finally{  //chiudo la connessione in ogni caso
    if($connectionOK)
        $connection->closeDBConnection();
}

if(isset($_GET['logout'])){
    unset($_SESSION['username']);
    header("Location: index.php");
  	exit();
}

$abbonamenti = "";
$listaAbbonamenti = "";
$categorie = "";
$listaCategorie = "";
$piattaforme = "";
$listaPiattaforme = "";

try{
    $connectionOK = $connection->openDBConnection();
    if($connectionOK){
        $abbonamenti = $connection->getListAbbonamenti();
        $categorie = $connection->getListCategorie();
        $piattaforme = $connection->getListPiattaforme();
    }
    else
        header("Location: 500.php");
}
catch(mysqli_sql_exception $e){   //se c'è un errore a livello database
    header("Location: 500.php");
}
finally{  //chiudo la connessione in ogni caso
    if($connectionOK)
        $connection->closeDBConnection();
}

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

$paginaHTML = str_replace('[listaAbbonamenti]', $listaAbbonamenti, $paginaHTML);
$paginaHTML = str_replace('[listaCategorie]', $listaCategorie, $paginaHTML);
$paginaHTML = str_replace('[listaPiattaforme]', $listaPiattaforme, $paginaHTML);

$messaggioErroreOutput = "<div class=\"divForm\"><h2>Risultato</h2><p class=\"itemCentered errorFormAdmin\">Qualcosa è andato storto! Gli errori rilevati sono stati stampati in cima al form su cui stavi lavorando.</p></div>";

$messaggiInserimento = "";

if (isset($_POST['inserisciVideogioco'])) {
	$piat = array();
    $cat = array();
    foreach ($_POST as $p){
        if (str_contains($p,"Piattaforma")){
            $p = str_replace("Piattaforma","",$p);
            array_push($piat,$p);
        }
        else if(str_contains($p,"Categoria")){
            $p = str_replace("Categoria","",$p);
            array_push($cat,$p);
        }
    }

    $messaggiInserimento .= "<ul class=\"itemCentered errorFormAdmin\">";

    $codice = pulisciInput($_POST['codice']);
    $titolo = pulisciInput($_POST['titolo']);
    $pegi = $_POST['pegi'];
    $dataUscita = pulisciInput($_POST['data-uscita']);
    $prezzo = pulisciInput($_POST['prezzo']);
    $casaSviluppatrice = pulisciInput($_POST['casa-sviluppatrice']);
    $descrizione = pulisciInput($_POST['descrizione']);
    $img = $_POST['immagine'];
    $abb = $_POST['abbonamentoMin'];

	if(!preg_match("/^[0-9]{8,8}$/",$codice))
		$messaggiInserimento .= "<li>Il codice contiene solo numeri e deve essere di 8 caratteri</li>";

    if(!preg_match("/^[A-Za-z0-9\ \']{2,20}$/",$titolo))
        $messaggiInserimento .= "<li>Il titolo non può contenere caratteri speciali, deve contenere almeno 2 caratteri e massimo 20</li>";

    if(strlen($dataUscita) == 0)
        $messaggiPerForm .= "<li>La data di uscita non può essere vuota, formato dd/mm/yyyy</li>";
	
	if(!preg_match("/^([0-9]{1,3})$/",$prezzo))
		$messaggiInserimento .= "<li>Il prezzo deve essere compreso tra 0 e 999</li>";
   
	if(!preg_match("/^[A-Za-z0-9\ \']{2,30}$/",$casaSviluppatrice))
        $messaggiInserimento .= "<li>La casa sviluppatrice contiene solo lettere o numeri, deve contenere almeno 2 caratteri e massimo 30</li>";

    if($img == "")
        $messaggiInserimento .= "<li>Nessun immagine selezionata</li>";
    
	if(!preg_match("/^[\s\S]{20,1000}$/",$descrizione))
        $messaggiInserimento .= "<li>La descrizione deve essere di almeno 20 caratteri max 1000</li>";

    if(count($piat) == 0)
        $messaggiInserimento .= "<li>Selezionare almeno una piattaforma</li>";

    if(count($cat) == 0)
        $messaggiInserimento .= "<li>Selezionare almeno una categoria</li>";

	$messaggiInserimento .= "</ul>";

	if($messaggiInserimento == "<ul class=\"itemCentered errorFormAdmin\"></ul>"){
        try{
            $connectionOK = $connection->openDBConnection();
            if($connectionOK){
                if($connection->getGiocoByCodice($codice) == null){  //se il codice non è già presente
                    $connection->insertGioco($codice,$titolo,$descrizione,$prezzo,$dataUscita,$pegi,$casaSviluppatrice,$img);
                    $connection->insertCategorieGioco($codice, $cat);
                    $connection->insertPiattaformeGioco($codice, $piat);
                    $connection->insertAbbonamentiGioco($codice, $abb);
                    $paginaHTML = str_replace('[messaggioOutput]', "<div class=\"divForm\"><h2>Risultato</h2><p class=\"itemCentered confermaOperazioneAdmin\">Inserimento avvenuto con successo</p></div>", $paginaHTML);
                }       
                else{
                    $messaggiInserimento = "<p class=\"itemCentered errorFormAdmin\">Codice gioco già utilizzato, si prega di usarne un altro</p>";
                    $paginaHTML = str_replace('[messaggioOutput]', $messaggioErroreOutput, $paginaHTML);
                }
            }
            else
                header("Location: 500.php");
        }
        catch(mysqli_sql_exception $e){   //se c'è un errore a livello database
            header("Location: 500.php");
        }
        finally{  //chiudo la connessione in ogni caso
            if($connectionOK)
                $connection->closeDBConnection();
        }
	} 
    else
        $paginaHTML = str_replace('[messaggioOutput]', $messaggioErroreOutput, $paginaHTML);

}
$paginaHTML = str_replace('[messaggiInserimento]', $messaggiInserimento, $paginaHTML);

$messaggioRimozione = "";
if (isset($_POST['rimuoviVideogioco'])) {
    $codice = pulisciInput($_POST['codice-rimozione']);

	if(!preg_match("/^[0-9]{8,8}$/",$codice)){
        $messaggioRimozione .= "<p class=\"itemCentered errorFormAdmin\">Il codice contiene solo numeri e deve essere di 8 caratteri</p>";
        $paginaHTML = str_replace('[messaggioOutput]', $messaggioErroreOutput, $paginaHTML);	
    }

	if($messaggioRimozione == ""){
        try{
            $connectionOK = $connection->openDBConnection();
            if($connectionOK){
                if(!$connection->getGiocoByCodice($codice) == null){  //se il codice è presente
                    $rimozione = $connection->rimuoviGioco($codice);
                    if($rimozione)
                        $paginaHTML = str_replace('[messaggioOutput]', "<div class=\"divForm\"><h2>Risultato</h2><p class=\"itemCentered confermaOperazioneAdmin\">Rimozione avvenuta con successo</p></div>", $paginaHTML);
                }
                else{   //se il codice non è presente non è possibile rimuoverlo
                    $messaggioRimozione = "<p class=\"itemCentered errorFormAdmin\">Codice gioco non presente</p>";
                    $paginaHTML = str_replace('[messaggioOutput]', $messaggioErroreOutput, $paginaHTML);
                }
            }
            else
                header("Location: 500.php");
        }
        catch(mysqli_sql_exception $e){   //se c'è un errore a livello database
            header("Location: 500.php");
        }
        finally{  //chiudo la connessione in ogni caso
            if($connectionOK)
                $connection->closeDBConnection();
        }
	}
}
$paginaHTML = str_replace('[messaggioRimozione]', $messaggioRimozione, $paginaHTML);

$messaggioModifica = "";
if (isset($_POST['modificaAbbonamento'])) {

    $prezzo = pulisciInput($_POST['nuovo-costo']);
    $abb = pulisciInput($_POST['abbonamento']);

	if(!preg_match("/^[0-9]{1,3}$/",$prezzo)){
		$messaggioModifica = "<p class=\"itemCentered errorFormAdmin\">Il prezzo deve essere compreso tra 0 e 999</p>";
        $paginaHTML = str_replace('[messaggioOutput]', $messaggioErroreOutput, $paginaHTML);
    }

    if($messaggioModifica == ""){
        try{
            $connectionOK = $connection->openDBConnection();
            if($connectionOK){
                if($connection->modificaPrezzoAbbonamento($abb,$prezzo)){  //se il prezzo è stato modificato
                    $paginaHTML = str_replace('[messaggioOutput]', "<div class=\"divForm\"><h2>Risultato</h2><p class=\"itemCentered confermaOperazioneAdmin\">Modifica avvenuta con successo</p></div>", $paginaHTML);
                }
                else{   //se il prezzo è già quello impostato
                    $messaggioModifica = "<p class=\"itemCentered errorFormAdmin\">Il nuovo prezzo dell'abbonamento è già quello impostato</p>";
                    $paginaHTML = str_replace('[messaggioOutput]', $messaggioErroreOutput, $paginaHTML);
                }
            }
            else
                header("Location: 500.php");
        }
        catch(mysqli_sql_exception $e){   //se c'è un errore a livello database
            header("Location: 500.php");
        }
        finally{  //chiudo la connessione in ogni caso
            if($connectionOK)
                $connection->closeDBConnection();
        }
    }       
}
$paginaHTML = str_replace('[messaggioModifica]', $messaggioModifica, $paginaHTML);
$paginaHTML = str_replace('[messaggioOutput]', "", $paginaHTML);
echo $paginaHTML;
?>