<?php
require_once "templatedbConnection.php";
use DB\DBAccess;

$paginaHTML = file_get_contents('abbonamenti.html');

$menuLoginProfilo = "<li class=\"login\"><a href=\"login.php\">Accedi</a></li>";
session_start();
if (isset($_SESSION['username'])) 
    $menuLoginProfilo = "<li class=\"profile\"><a href=\"profilo.php\">Profilo</a></li>";

$paginaHTML = str_replace('[loginProfilo]', $menuLoginProfilo, $paginaHTML);

$connection = new DBAccess();
$connectionOK = $connection->openDBConnection();

$abbonamenti = "";
$listaAbbonamenti = "";

if(!$connectionOK)
{
    $abbonamenti = $connection->getListAbbonamenti();
    //$connection->closeDBConnection();

    if($abbonamenti)
    {
        $listaAbbonamenti .= "<div class=\"abbonamentiAbb\">";
        foreach($abbonamenti as $abb)
        {
            $listaAbbonamenti .= "<div class=\"targhettaAbb\">";
            $nome = $abb['nome'];
            $listaAbbonamenti .= "<h2><strong>$nome</strong></h2>";
            $img = $abb['immagine'];
            $listaAbbonamenti .= "<img src=\"assets/$img\" alt=\"\">";
            $descrizione = $abb['descrizione'];
            $listaAbbonamenti .= "<p>$descrizione</p>";
            $costo = $abb['prezzo'];
            $listaAbbonamenti .= "<p><strong>Costo annuale:</strong> $costo €</p>";
            $listaAbbonamenti .= "<a role=\"button\" href=\"abbonamenti.php?abbonamento=$nome\">Abbonati!</a>";
            $listaAbbonamenti .= "[messaggio$nome]";
            $listaAbbonamenti .= "</div>";
        }
        $listaAbbonamenti .= "</div>";
    }
    else
        $listaAbbonamenti .= "Non ci sono abbonamenti da visualizzare";
}
else
	//in fase di produzione rimuovere $connessioneOK
	$listaAbbonamenti = $connectionOK ."<p>I sistemi sono momentaneamente fuori servizio, ci scusiamo per il disagio.</p>";

$paginaHTML = str_replace('[listaAbbonamenti]', $listaAbbonamenti, $paginaHTML);

if(isset($_GET['abbonamento'])){
    $abb = $_GET['abbonamento'];
    if (!isset($_SESSION['username']))
        $paginaHTML = str_replace("[messaggio$abb]", "Prima di abbonarsi bisogna registrarsi", $paginaHTML);
    else
    {
        $utente = ($connection->getUtente($_SESSION['username'])[0]);
        if($utente['abbonamentoAttuale'] != NULL)
            $paginaHTML = str_replace("[messaggio$abb]", "Prima di abbonarsi ad un nuovo abbonamento bisogna disdire il vecchio", $paginaHTML);
        else
        {
            $result = $connection->acquistaAbbonamento($utente['username'], $abb);
            header("Location: /TecWeb-Project/acquistoCompletato.php");
        }
    }
}

$paginaHTML = str_replace('[messaggioBase]', "", $paginaHTML);
$paginaHTML = str_replace('[messaggioDeluxe]', "", $paginaHTML);
$paginaHTML = str_replace('[messaggioPremium]', "", $paginaHTML);
echo $paginaHTML;
?>