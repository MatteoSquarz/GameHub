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
    $connection->closeDBConnection();

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
            $listaAbbonamenti .= "<button onclick>Abbonati!</button>";
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

echo str_replace("[listaAbbonamenti]", $listaAbbonamenti, $paginaHTML);

?>