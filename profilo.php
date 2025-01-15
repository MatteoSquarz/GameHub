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
    header("Location: /TecWeb-project/index.php");
  	exit();
}

$connection = new DBAccess();

if(isset($_GET['disdici'])){
    $connectionOK = $connection->openDBConnection();
    $connection->disdiciAbbonamento($_SESSION['username']);
    $connection->closeDBConnection();
    header("Location: /TecWeb-project/profilo.php");
}

$vendite = "";
$listaGiochi = "";

if($connection->openDBConnection() && isset($_SESSION['username']))
{
    $utente = ($connection->getUtente($_SESSION['username'])[0]);
    $connection->closeDBConnection();

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
    if ($abbonamento == null){
        $imgAbb = "<img src=\"assets/no-abbonamento.png\" alt=\"\" class=\"profilePicture\">";
        $paginaHTML = str_replace('[immagine]', $imgAbb, $paginaHTML);
        $paginaHTML = str_replace('[Nome Abbonamento]', "Nessun abbonamento attivo", $paginaHTML);
        $paginaHTML = str_replace('[Data iscrizione]', "Nessun abbonamento attivo", $paginaHTML);
        $paginaHTML = str_replace('[Data scadenza]', "Nessun abbonamento attivo", $paginaHTML);
        $paginaHTML = str_replace('[pulsante disdici]', "", $paginaHTML);
    }
    else
    {
        if($connection->openDBConnection())
        {
            $abbonamentoImg = $connection->getImmagineAbbonamento($abbonamento);
            $connection->closeDBConnection();
        }
        else
            header("Location: /TecWeb-project/500.php");

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

    if($connection->openDBConnection())
    {
        $vendite = $connection->getAcquisti($_SESSION['username']);
        $connection->closeDBConnection();
    }
    else
        header("Location: /TecWeb-project/500.php");

    if($vendite == null)
        $listaGiochi = "<p class=\"noAcquisti\">Non hai ancora effettuato acquisti.</p>";
    else{
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
else
    header("Location: /TecWeb-project/500.php");

$paginaHTML = str_replace('[lista Giochi]', $listaGiochi, $paginaHTML);
$paginaHTML = str_replace('[loginProfilo]', $menuLoginProfilo, $paginaHTML);
echo $paginaHTML;
?>