<?php
require_once "templatedbConnection.php";
use DB\DBAccess;

$paginaHTML = file_get_contents('catalogo.html');

$menuLoginProfilo = "<li class=\"login\"><a href=\"login.php\">Accedi</a></li>";
session_start();
if (isset($_SESSION['username'])) 
    $menuLoginProfilo = "<li class=\"profile\"><a href=\"profilo.php\">Profilo</a></li>";

$paginaHTML = str_replace('[loginProfilo]', $menuLoginProfilo, $paginaHTML);

$connection = new DBAccess();
$connectionOK = $connection->openDBConnection();

$giochi = "";
$listaGiochi = "";

if(!$connectionOK)
{
    $giochi = $connection->getListGiochi();
    $connection->closeDBConnection();

    if($giochi)
    {
        $listaGiochi .= "<div class=\"card-container\">";
        foreach($giochi as $gioco)
        {
            $listaGiochi .= "<div class=\"game-card\" href=\"#GamePage\">";
            $img = $gioco['immagine'];
            $listaGiochi .= "<img class=\"game-card-image\" src=\"assets/game-covers/$img\" alt=\"\">";
            $listaGiochi .= "<div class=\"game-info\">";
            $titolo = $gioco['titolo'];
            $listaGiochi .= "<h3>$titolo</h3>";
            $codice = $gioco['codice'];
            $listaGiochi .= "<a class=\"game-page-link\" href=\"videogioco.php?codice=$codice\">Vai alla pagina dedicata</a>";
            $listaGiochi .= "</div>";
            $listaGiochi .= "</div>";
        }
        $listaGiochi .= "</div>";
    }
    else
        $listaGiochi .= "Non ci sono abbonamenti da visualizzare";
}
else
	//in fase di produzione rimuovere $connessioneOK
	$listaGiochi = $connectionOK ."<p>I sistemi sono momentaneamente fuori servizio, ci scusiamo per il disagio.</p>";

echo str_replace("[listaGiochi]", $listaGiochi, $paginaHTML);

?>