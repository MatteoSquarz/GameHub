<?php
require_once "utility.php";
require_once "templatedbConnection.php";
use DB\DBAccess;


$paginaHTML = file_get_contents('template/modificaVideogioco.html');

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


$gioco = null;
$categorieGioco = "";
$piattaformeGioco = "";
$abbonamentiGioco = "";
$codice = $_GET['codice'];
try{
    $connectionOK = $connection->openDBConnection();
    if($connectionOK){
        if($connection->getGiocoByCodice($codice)){
            $gioco = $connection->getGiocoByCodice($codice)[0];
            $categorieGioco = $connection->getCategoriaByCodiceGioco($codice);
            $piattaformeGioco = $connection->getPiattaformaByCodiceGioco($codice);
            $abbonamentiGioco = $connection->getAbbonamentoByCodiceGioco($codice);
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
$gioco['descrizione'] = str_replace("'",'&apos;',$gioco['descrizione']);
$gioco['titolo'] = str_replace("'","&apos;",$gioco['titolo']);
$gioco['casaSviluppatrice'] = str_replace("'","&apos;",$gioco['casaSviluppatrice']);
$paginaHTML = str_replace('[codiceGioco]', $gioco['codice'], $paginaHTML);
$paginaHTML = str_replace('[titoloGioco]', $gioco['titolo'], $paginaHTML);
$paginaHTML = str_replace('[dataUscitaGioco]', $gioco['dataUscita'], $paginaHTML);
$paginaHTML = str_replace('[prezzoGioco]', $gioco['prezzo'], $paginaHTML);
$paginaHTML = str_replace('[produttoreGioco]', $gioco['casaSviluppatrice'], $paginaHTML);
$paginaHTML = str_replace('[descrizioneGioco]', $gioco['descrizione'], $paginaHTML);
$paginaHTML = str_replace('[modificaVideogioco]', "modificaVideogioco.php?codice=" . $codice, $paginaHTML);




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

$pegi = array("3","7","12","16","18");
$listaPegi = "";
foreach($pegi as $numPegi){
    if($numPegi != $gioco['pegi'])
        $listaPegi .= "<option value=\"$numPegi\">$numPegi</option>";
    else
        $listaPegi .= "<option value=\"$numPegi\" selected>$numPegi</option>";
}
$paginaHTML = str_replace('[listaPegi]', $listaPegi, $paginaHTML);

foreach($abbonamenti as $abbonamento){
    $nome = $abbonamento['nome'];
    if($nome != $abbonamentiGioco[0]['nome'])
        $listaAbbonamenti .= "<option value=\"$nome\">$nome</option>";
    else
        $listaAbbonamenti .= "<option value=\"$nome\" selected>$nome</option>";
}

$listaPiattaformeGioco = array();
foreach($piattaformeGioco as $piattaformaGioco){
    $piat = $piattaformaGioco['piattaforma'];
    $listaPiattaformeGioco[] = $piat;
}

foreach($piattaforme as $piattaforma){
    $nome = $piattaforma['nome'];
    $value = strip_tags($nome);
    $listaPiattaforme .= "<div class=\"sceltaPiattaforma\">";
    if(in_array($nome,$listaPiattaformeGioco))
        $listaPiattaforme .= "<input type='checkbox' id=\"$value\" name=\"$value\" value='Piattaforma$nome' checked/>";
    else
        $listaPiattaforme .= "<input type='checkbox' id=\"$value\" name=\"$value\" value='Piattaforma$nome'/>";
    $listaPiattaforme .= "<label for=\"$value\"> $nome</label>";
    $listaPiattaforme .= "</div>";
}


$listaCategorieGioco = array();
foreach($categorieGioco as $categoriaGioco){
    $cat = $categoriaGioco['categoria'];
    $listaCategorieGioco[] = $cat;
}
$count = 0;
foreach($categorie as $categoria){
    if($count % 5 == 0) { //apre un nuovo gruppo di 5 categorie
        $listaCategorie .= "<div class=\"gruppoScelteCategoria\">";
    }
    $nome = $categoria['nome'];
    $value = strip_tags($nome);
    $listaCategorie .= "<div>";
    if(in_array($nome,$listaCategorieGioco))
        $listaCategorie .= "<input type=\"checkbox\" id=\"$value\" name=\"$value\" value='Categoria$nome' checked/>";
    else
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

$messaggioErroreOutput = "<div class=\"divForm\"><h2>Risultato</h2><p id='outputErrore' class=\"itemCentered errorFormAdmin\">Qualcosa è andato storto! Gli errori rilevati sono stati stampati all'interno del <span lang='en'>form</span> su cui stavi lavorando.</p></div>";


$erroreTitoloIns = "";
$erroreDataIns = "";
$errorePrezzoIns = "";
$erroreProduttoreIns = "";
$erroreDescrizioneIns = "";
$errorePiattaformeIns = "";
$erroreCategorieIns = "";

if (isset($_POST['modificaVideogioco'])) {
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

    $titolo = pulisciCampiAdmin($_POST['titolo']);
    $pegi = $_POST['pegi'];
    $dataUscita = pulisciInput($_POST['data-uscita']);
    $prezzo = pulisciInput($_POST['prezzo']);
    $casaSviluppatrice = pulisciCampiAdmin($_POST['casa-sviluppatrice']);
    $descrizione = pulisciCampiAdmin($_POST['descrizione']);
    $abb = $_POST['abbonamentoMinModifica'];


    if(strlen($titolo) == 0)
		$erroreTitoloIns .= "<strong class='errorFormAdmin'>Inserire il titolo</strong>";
    else if(!preg_match("/^[A-Za-z0-9<>=\"\ \'\/]{2,50}$/",$titolo))
        $erroreTitoloIns .= "<strong class='errorFormAdmin'>Il titolo non può contenere caratteri speciali, deve contenere almeno 2 caratteri e massimo 50</strong>";

    if(strlen($dataUscita) == 0)
        $erroreDataIns .= "<strong class='errorFormAdmin'>Inserire la data di uscita</strong>";
    else if (!preg_match("/^\d{4}\-\d{2}\-\d{2}$/", $dataUscita)) {
        $erroreDataIns .= "<strong class='errorFormAdmin'>Formato data non corretto</strong>";
    }
	
    if(strlen($prezzo) == 0)
		$errorePrezzoIns .= "<strong class='errorFormAdmin'>Inserire il prezzo</strong>";
	else if(!preg_match("/^([0-9]{1,3})$/",$prezzo))
		$errorePrezzoIns .= "<strong class='errorFormAdmin'>Il prezzo deve essere compreso tra 0 e 999</strong>";
   
    if(strlen($casaSviluppatrice) == 0)
		$erroreProduttoreIns .= "<strong class='errorFormAdmin'>Inserire la casa sviluppatrice</strong>";
	else if(!preg_match("/^[A-Za-z0-9<>=\"\ \'\/]{2,50}$/",$casaSviluppatrice))
        $erroreProduttoreIns .= "<strong class='errorFormAdmin'>La casa sviluppatrice contiene solo lettere o numeri, deve contenere almeno 2 caratteri e massimo 50</strong>";
    
    if(strlen($descrizione) == 0)
		$erroreDescrizioneIns .= "<strong class='errorFormAdmin'>Inserire la descrizione</strong>";
	else if(!preg_match("/^[\s\S]{20,1000}$/",$descrizione))
        $erroreDescrizioneIns .= "<strong class='errorFormAdmin'>La descrizione deve essere di almeno 20 caratteri e massimo 1000</strong>";

    if(count($piat) == 0)
        $errorePiattaformeIns .= "<strong class='errorFormAdmin itemCentered'>Selezionare almeno una piattaforma</strong>";

    if(count($cat) == 0)
        $erroreCategorieIns .= "<strong class='errorFormAdmin'>Selezionare almeno una categoria</strong>";

    $erroriIns = $erroreTitoloIns . $erroreDataIns . $errorePrezzoIns . $erroreProduttoreIns . $erroreDescrizioneIns . $errorePiattaformeIns . $erroreCategorieIns;

	if($erroriIns == ""){  //se non ci sono errori
        try{
            $connectionOK = $connection->openDBConnection();
            if($connectionOK){
                $titolo = str_replace('"','\"',$titolo);
                $casaSviluppatrice = str_replace('"','\"',$casaSviluppatrice);
                $descrizione = str_replace('"','\"',$descrizione);
                $connection->modificaGioco($codice,$titolo,$descrizione,$prezzo,$dataUscita,$pegi,$casaSviluppatrice,$cat,$piat,$abb);
                header("Location: catalogo.php?modifica=1");       
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

$paginaHTML = str_replace('[erroreTitoloIns]', $erroreTitoloIns, $paginaHTML);
$paginaHTML = str_replace('[erroreDataIns]', $erroreDataIns, $paginaHTML);
$paginaHTML = str_replace('[errorePrezzoIns]', $errorePrezzoIns, $paginaHTML);
$paginaHTML = str_replace('[erroreProduttoreIns]', $erroreProduttoreIns, $paginaHTML);
$paginaHTML = str_replace('[erroreDescrizioneIns]', $erroreDescrizioneIns, $paginaHTML);
$paginaHTML = str_replace('[errorePiattaformeIns]', $errorePiattaformeIns, $paginaHTML);
$paginaHTML = str_replace('[erroreCategorieIns]', $erroreCategorieIns, $paginaHTML);
$paginaHTML = str_replace('[messaggioOutput]', "", $paginaHTML);
echo $paginaHTML;

?>