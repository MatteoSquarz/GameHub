<?php
require_once "templatedbConnection.php";
use DB\DBAccess;

$paginaHTML = file_get_contents('abbonamenti.html');

$connection = new DBAccess();
$connectionOK = $connection->openDBConnection();

$abbonamenti = "";
$listaAbbonamenti = "";

if(!$connectionOK)
{
    $abbonamenti = $connection->getListAbbonamenti();
    $connection->closeConnection();

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
            $listaAbbonamenti .= "<p><strong>Costo:</strong>$costo €</p>";
            $livello = $abb['livello'];
            if($livello == 2)
            {
                $listaAbbonamenti .= "<form action=\"/submit\" method=\"post\">";
                $listaAbbonamenti .= "<label for=\"piattaforma\">Seleziona il sistema:</label>";
                $listaAbbonamenti .= "<select id=\"piattaforma\" name=\"piattaforma\" required>";
                $listaAbbonamenti .= "<option value=\"\" disabled selected>Seleziona un'opzione</option>";
                $listaAbbonamenti .= "<option value=\"playstation\">PlayStation</option>";
                $listaAbbonamenti .= "<option value=\"xbox\">Xbox</option>";
                $listaAbbonamenti .= "<option value=\"pc\">PC</option>";
                $listaAbbonamenti .= "<option value=\"Nintendo\">Nintendo</option>";
                $listaAbbonamenti .= "</select>";
            }
            $listaAbbonamenti .= "<button onclick>Abbonati!</button>";
            if($livello == 2)
                $listaAbbonamenti .= "</form>";
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