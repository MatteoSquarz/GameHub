<?php
require_once "templatedbConnection.php";
use DB\DBAccess;
$paginaHTML = file_get_contents('profilo.html');

$menuLoginProfilo = "<li class=\"login\"><a href=\"login.php\">Accedi</a></li>";
session_start();
if (isset($_SESSION['username'])) 
    $menuLoginProfilo = "<li id=\"currentMenu\" class=\"profile\">Profilo</li>";

if(isset($_GET['logout'])){
    unset($_SESSION['username']);
    header("Location: /index.php");
  	exit();
}

$connection = new DBAccess();
$connectionOK = $connection->openDBConnection();

$utenti = "";
$vendite = "";
$listaGiochi = "";

if(!$connectionOK)
{
    $utenti = $connection->getUtente($_SESSION['username']);
    foreach($utenti as $utente)
    {        
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
        $abbonamento = $utente['abbonamentoAttuale'];
        $abbonamentoImg = $connection->getImmagineAbbonamento($abbonamento);
        $imgAbb = "<img src=\"assets/$abbonamentoImg\" alt=\"\" class=\"profilePicture\">";
        $paginaHTML = str_replace('[immagine]', $imgAbb, $paginaHTML);
        $paginaHTML = str_replace('[Nome Abbonamento]', $abbonamento, $paginaHTML);
        $dataInizio = $utente['dataInizio'];
        $paginaHTML = str_replace('[Data iscrizione]', $dataInizio, $paginaHTML);
        $dataFine = $utente['dataFine'];
        $paginaHTML = str_replace('[Data scadenza]', $dataFine, $paginaHTML);
    }

    $vendite = $connection->getAcquisti($_SESSION['username']);
    $cnt = 0;
    foreach($vendite as $vendita)
    {
        $codice = $vendita['videogioco'];
        $videogiochi = $connection->getGiocoByCodice($codice);
        $listaGiochi .= "<li class=\"gameItem\">";
        $img = $videogiochi[$cnt]['immagine'];
        $listaGiochi .= "<img src=\"assets/game-covers/$img\" alt=\"\" class=\"gameThumbnail\">";
        $listaGiochi .= "<div class=\"gameInfo\">";
        $nome = $videogiochi[$cnt]['titolo'];
        $listaGiochi .= "<h3>$nome</h3>";
        $data = $vendita['data'];
        $listaGiochi .= "<p class=\"purchaseDate\">Acquistato il: $data</p>";
        $listaGiochi .= "</div>";
        $listaGiochi .= "</li>";
    }
}
else
    $paginaGioco = $connectionOK ."<p>I sistemi sono momentaneamente fuori servizio, ci scusiamo per il disagio.</p>";

$paginaHTML = str_replace('[lista Giochi]', $listaGiochi, $paginaHTML);
$paginaHTML = str_replace('[loginProfilo]', $menuLoginProfilo, $paginaHTML);
echo $paginaHTML;
?>