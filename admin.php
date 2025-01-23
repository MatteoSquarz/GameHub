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
    $value = strip_tags($nome);
    $listaPiattaforme .= "<div class=\"sceltaPiattaforma\">";
    $listaPiattaforme .= "<input type='checkbox' id=\"$value\" name=\"$value\" value='Piattaforma$nome' />";
    $listaPiattaforme .= "<label for=\"$value\"> $nome</label>";
    $listaPiattaforme .= "</div>";
}

$count = 0;
foreach($categorie as $categoria){
    if($count % 5 == 0) { //apre un nuovo gruppo di 5 categorie
        $listaCategorie .= "<div class=\"gruppoScelteCategoria\">";
    }
    $nome = $categoria['nome'];
    $value = strip_tags($nome);
    $listaCategorie .= "<div>";
    $listaCategorie .= "<input type=\"checkbox\" id=\"$value\" name=\"$value\" value='Categoria$nome' />";
    $listaCategorie .= "<label for=\"$value\"> $nome</label>";
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

$messaggioErroreOutput = "<div class=\"divForm\"><h2>Risultato</h2><p class=\"itemCentered errorFormAdmin\">Qualcosa è andato storto! Gli errori rilevati sono stati stampati all'interno del <span lang='en'>form</span> su cui stavi lavorando.</p></div>";


$erroreCodiceIns = "";
$erroreTitoloIns = "";
$erroreDataIns = "";
$errorePrezzoIns = "";
$erroreProduttoreIns = "";
$erroreDescrizioneIns = "";
$erroreImmagineIns = "";
$errorePiattaformeIns = "";
$erroreCategorieIns = "";

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

    $codice = pulisciInput($_POST['codice']);
    $titolo = pulisciInput($_POST['titolo']);
    $pegi = $_POST['pegi'];
    $dataUscita = pulisciInput($_POST['data-uscita']);
    $prezzo = pulisciInput($_POST['prezzo']);
    $casaSviluppatrice = pulisciInput($_POST['casa-sviluppatrice']);
    $descrizione = pulisciInput($_POST['descrizione']);
    $img = $_POST['immagine'];
    $abb = $_POST['abbonamentoMin'];

    if(strlen($codice) == 0)
		$erroreCodiceIns .= "<strong class='errorFormAdmin'>Inserire il codice</strong>";
	else if(!preg_match("/^[0-9]{8,8}$/",$codice))
		$erroreCodiceIns .= "<strong class='errorFormAdmin'>Il codice contiene solo numeri e deve essere di 8 caratteri</strong>";

    if(strlen($titolo) == 0)
		$erroreTitoloIns .= "<strong class='errorFormAdmin'>Inserire il titolo</strong>";
    else if(!preg_match("/^[A-Za-z0-9\ \']{2,20}$/",$titolo))
        $erroreTitoloIns .= "<strong class='errorFormAdmin'>Il titolo non può contenere caratteri speciali, deve contenere almeno 2 caratteri e massimo 20</strong>";

    if(strlen($dataUscita) == 0)
        $erroreDataIns .= "<strong class='errorFormAdmin'>Inserire la data di uscita</strong>";
	
    if(strlen($prezzo) == 0)
		$errorePrezzoIns .= "<strong class='errorFormAdmin'>Inserire il prezzo</strong>";
	else if(!preg_match("/^([0-9]{1,3})$/",$prezzo))
		$errorePrezzoIns .= "<strong class='errorFormAdmin'>Il prezzo deve essere compreso tra 0 e 999</strong>";
   
    if(strlen($casaSviluppatrice) == 0)
		$erroreProduttoreIns .= "<strong class='errorFormAdmin'>Inserire la casa sviluppatrice</strong>";
	else if(!preg_match("/^[A-Za-z0-9\ \']{2,30}$/",$casaSviluppatrice))
        $erroreProduttoreIns .= "<strong class='errorFormAdmin'>La casa sviluppatrice contiene solo lettere o numeri, deve contenere almeno 2 caratteri e massimo 30</strong>";

    if($img == "")
        $erroreImmagineIns .= "<strong class='errorFormAdmin'>Nessun immagine selezionata</strong>";
    
    if(strlen($descrizione) == 0)
		$erroreDescrizioneIns .= "<strong class='errorFormAdmin'>Inserire la descrizione</strong>";
	else if(!preg_match("/^[\s\S]{20,1000}$/",$descrizione))
        $erroreDescrizioneIns .= "<strong class='errorFormAdmin'>La descrizione deve essere di almeno 20 caratteri e massimo 1000</strong>";

    if(count($piat) == 0)
        $errorePiattaformeIns .= "<strong class='errorFormAdmin itemCentered'>Selezionare almeno una piattaforma</strong>";

    if(count($cat) == 0)
        $erroreCategorieIns .= "<strong class='errorFormAdmin'>Selezionare almeno una categoria</strong>";

    $erroriIns = $erroreCodiceIns . $erroreTitoloIns . $erroreDataIns . $errorePrezzoIns . $erroreProduttoreIns . $erroreDescrizioneIns . $erroreImmagineIns . $errorePiattaformeIns . $erroreCategorieIns;

	if($erroriIns == ""){  //se non ci sono errori
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
                    $erroreCodiceIns = "<strong class=\"errorFormAdmin\">Codice gioco già utilizzato, si prega di usarne un altro</strong>";
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

$paginaHTML = str_replace('[erroreCodiceIns]', $erroreCodiceIns, $paginaHTML);
$paginaHTML = str_replace('[erroreTitoloIns]', $erroreTitoloIns, $paginaHTML);
$paginaHTML = str_replace('[erroreDataIns]', $erroreDataIns, $paginaHTML);
$paginaHTML = str_replace('[errorePrezzoIns]', $errorePrezzoIns, $paginaHTML);
$paginaHTML = str_replace('[erroreProduttoreIns]', $erroreProduttoreIns, $paginaHTML);
$paginaHTML = str_replace('[erroreDescrizioneIns]', $erroreDescrizioneIns, $paginaHTML);
$paginaHTML = str_replace('[erroreImmagineIns]', $erroreImmagineIns, $paginaHTML);
$paginaHTML = str_replace('[errorePiattaformeIns]', $errorePiattaformeIns, $paginaHTML);
$paginaHTML = str_replace('[erroreCategorieIns]', $erroreCategorieIns, $paginaHTML);



$erroreCodiceDel = "";
if (isset($_POST['rimuoviVideogioco'])) {
    $codice = pulisciInput($_POST['codice-rimozione']);

	if(strlen($codice) == 0)
		$erroreCodiceDel .= "<strong class=\"errorFormAdmin\">Inserire il codice</strong>";
	else if(!preg_match("/^[0-9]{8,8}$/",$codice)){
        $erroreCodiceDel .= "<strong class=\"errorFormAdmin\">Il codice contiene solo numeri e deve essere di 8 caratteri</strong>";
        $paginaHTML = str_replace('[messaggioOutput]', $messaggioErroreOutput, $paginaHTML);	
    }

	if($erroreCodiceDel == ""){
        try{
            $connectionOK = $connection->openDBConnection();
            if($connectionOK){
                if(!$connection->getGiocoByCodice($codice) == null){  //se il codice è presente
                    $rimozione = $connection->rimuoviGioco($codice);
                    if($rimozione)
                        $paginaHTML = str_replace('[messaggioOutput]', "<div class=\"divForm\"><h2>Risultato</h2><p class=\"itemCentered confermaOperazioneAdmin\">Rimozione avvenuta con successo</p></div>", $paginaHTML);
                }
                else{   //se il codice non è presente non è possibile rimuoverlo
                    $erroreCodiceDel = "<strong class=\"itemCentered errorFormAdmin\">Codice gioco non presente</strong>";
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
$paginaHTML = str_replace('[erroreCodiceDel]', $erroreCodiceDel, $paginaHTML);


$errorePrezzoMod = "";
if (isset($_POST['modificaAbbonamento'])) {

    $prezzo = pulisciInput($_POST['nuovo-costo']);
    $abb = pulisciInput($_POST['abbonamento']);

	if(strlen($prezzo) == 0)
		$errorePrezzoMod .= "<strong class=\"errorFormAdmin\">Inserire il prezzo</strong>";
	else if(!preg_match("/^[0-9]{1,3}$/",$prezzo)){
		$errorePrezzoMod = "<strong class=\"errorFormAdmin\">Il prezzo deve essere compreso tra 0 e 999</strong>";
        $paginaHTML = str_replace('[messaggioOutput]', $messaggioErroreOutput, $paginaHTML);
    }

    if($errorePrezzoMod == ""){
        try{
            $connectionOK = $connection->openDBConnection();
            if($connectionOK){
                if($connection->modificaPrezzoAbbonamento($abb,$prezzo)){  //se il prezzo è stato modificato
                    $paginaHTML = str_replace('[messaggioOutput]', "<div class=\"divForm\"><h2>Risultato</h2><p class=\"itemCentered confermaOperazioneAdmin\">Modifica avvenuta con successo</p></div>", $paginaHTML);
                }
                else{   //se il prezzo è già quello impostato
                    $errorePrezzoMod = "<strong class=\"errorFormAdmin\">Il nuovo prezzo dell'abbonamento è già quello impostato</strong>";
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
$paginaHTML = str_replace('[errorePrezzoMod]', $errorePrezzoMod, $paginaHTML);
$paginaHTML = str_replace('[messaggioOutput]', "", $paginaHTML);
echo $paginaHTML;
?>