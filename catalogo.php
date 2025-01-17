<?php
require_once "templatedbConnection.php";
use DB\DBAccess;

$paginaHTML = file_get_contents('template/catalogo.html');

$menuLoginProfilo = "<li class=\"login\"><a href=\"login.php\">Accedi</a></li>";
session_start();
if (isset($_SESSION['username'])) 
    $menuLoginProfilo = "<li class=\"profile\"><a href=\"profilo.php\">Profilo</a></li>";

$paginaHTML = str_replace('[loginProfilo]', $menuLoginProfilo, $paginaHTML);

$connection = new DBAccess();
$connectionOK = false;

$giochi = "";
$listaGiochi = "";

try{
    $connectionOK = $connection->openDBConnection();
    if($connectionOK){
        $giochi = $connection->getListGiochi();
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

if($giochi){
    $listaGiochi .= "<div class=\"card-container\">";
    foreach($giochi as $gioco){
        $listaGiochi .= "<div class=\"game-card\">";
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
    $listaGiochi .= "<p>Non ci sono giochi da visualizzare</p>";


echo str_replace("[listaGiochi]", $listaGiochi, $paginaHTML);

?>