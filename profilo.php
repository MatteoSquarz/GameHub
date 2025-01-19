<?php
require_once "templatedbConnection.php";
use DB\DBAccess;
$paginaHTML = file_get_contents('template/profilo.html');

$menuLoginProfilo = "<li class=\"login\"><a href=\"login.php\">Accedi</a></li>";
session_start();
if (isset($_SESSION['username'])) 
    $menuLoginProfilo = "<li id=\"currentMenu\" class=\"profile\">Profilo</li>";

if(isset($_GET['logout'])){
    unset($_SESSION['username']);
    header("Location: index.php");
  	exit();
}

$connection = new DBAccess();
$connectionOK = false;

if(isset($_GET['disdici'])){
    try{
        $connectionOK = $connection->openDBConnection();
        if($connectionOK){
            $connection->disdiciAbbonamento($_SESSION['username']);
            header("Location: profilo.php");
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

$utente = "";
$abbonamento = "";
$abbonamentoImg = "";
$vendite = "";
$listaGiochi = "";

if(!isset($_SESSION['username'])){   //qualcuno prova ad accedere alla pagina senza essere loggato
    header("Location: login.php");
    exit();
}

try{
    $connectionOK = $connection->openDBConnection();
    if($connectionOK){
        $utente = ($connection->getUtente($_SESSION['username'])[0]);
        if($utente){
            $abbonamento = $utente['abbonamentoAttuale'];
            $abbonamentoImg = $connection->getImmagineAbbonamento($abbonamento);
            $vendite = $connection->getAcquisti($_SESSION['username']);
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

if($utente){
    $username = $utente['username'];
    $paginaHTML = str_replace('[Nome Utente]', $username, $paginaHTML);
    $email = $utente['email'];
    $paginaHTML = str_replace('[Email]', $email, $paginaHTML);
    $nome = $utente['nome'];
    $paginaHTML = str_replace('[Nome]', $nome, $paginaHTML);
    $cognome = $utente['cognome'];
    $paginaHTML = str_replace('[Cognome]', $cognome, $paginaHTML);
    $dataNascita = $utente['dataNascita'];
    $paginaHTML = str_replace('[Data di Nascita]', $dataNascita, $paginaHTML);

    if ($abbonamento == null){  //se non ha un abbonamento
        $imgAbb = "<img src=\"assets/no-abbonamento.png\" alt=\"\" class=\"profilePicture\">";
        $paginaHTML = str_replace('[immagine]', $imgAbb, $paginaHTML);
        $paginaHTML = str_replace('[Nome Abbonamento]', "Nessun abbonamento attivo", $paginaHTML);
        $paginaHTML = str_replace('[Data iscrizione]', "Nessun abbonamento attivo", $paginaHTML);
        $paginaHTML = str_replace('[Data scadenza]', "Nessun abbonamento attivo", $paginaHTML);
        $paginaHTML = str_replace('[pulsante disdici]', "", $paginaHTML);
    }
    else{   //se ha un abbonamento
        $imgAbb = "<img src=\"assets/$abbonamentoImg\" alt=\"\" class=\"profilePicture\">";
        $paginaHTML = str_replace('[immagine]', $imgAbb, $paginaHTML);
        $paginaHTML = str_replace('[Nome Abbonamento]', $abbonamento, $paginaHTML);
        $dataInizio = $utente['dataInizio'];
        $paginaHTML = str_replace('[Data iscrizione]', $dataInizio, $paginaHTML);
        $dataFine = $utente['dataFine'];
        $paginaHTML = str_replace('[Data scadenza]', $dataFine, $paginaHTML);
        $disdici = "<a role=\"button\" class=\"buttonBoxProfile\" href=\"profilo.php?disdici=1\">Disdici</a>";
        $paginaHTML = str_replace('[pulsante disdici]', $disdici, $paginaHTML);
    }    

    if($vendite == null)  //se non ha effettuato acquisti
        $listaGiochi = "<h3 class=\"noAcquisti\">Non hai ancora effettuato acquisti.</h3>";
    else{    //se ha effettuato acquisti
        foreach($vendite as $vendita){
            $codice = $vendita['videogioco'];
            $listaGiochi .= "<li class=\"gameItem\">";
            $img = $vendita['immagine'];
            $listaGiochi .= "<img src=\"assets/game-covers/$img\" alt=\"\" class=\"gameThumbnail\">";
            $listaGiochi .= "<div class=\"gameInfo\">";
            $nome = $vendita['titolo'];
            $listaGiochi .= "<h3>$nome</h3>";
            $data = $vendita['data'];
            $listaGiochi .= "<p class=\"purchaseDate\">Acquistato il: $data</p>";
            $listaGiochi .= "</div>";
            $listaGiochi .= "</li>";
        }
    }
}

$paginaHTML = str_replace('[lista Giochi]', $listaGiochi, $paginaHTML);
$paginaHTML = str_replace('[loginProfilo]', $menuLoginProfilo, $paginaHTML);
echo $paginaHTML;
?>