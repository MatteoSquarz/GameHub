<?php
require_once "templatedbConnection.php";
use DB\DBAccess;

$paginaHTML = file_get_contents('videogioco.html');

$menuLoginProfilo = "<li class=\"login\"><a href=\"login.php\">Accedi</a></li>";
session_start();
if (isset($_SESSION['username'])) 
    $menuLoginProfilo = "<li class=\"profile\"><a href=\"profilo.php\">Profilo</a></li>";

$paginaHTML = str_replace('[loginProfilo]', $menuLoginProfilo, $paginaHTML);

$connection = new DBAccess();
$connectionOK = $connection->openDBConnection();

$giochi = "";
$paginaGioco = "";
$codice = $_GET['codice'];

if(!$connectionOK)
{
    $giochi = $connection->getGiocoByCodice($codice);
    $categorie = $connection->getCategoriaByCodiceGioco($codice);
    $piattaforme = $connection->getPiattaformaByCodiceGioco($codice);
    $abbonamenti = $connection->getAbbonamentoByCodiceGioco($codice);
    //$connection->closeDBConnection();

    $paginaGioco .= "<div class=\"backgroundPannelloVideogioco\">";
    foreach($giochi as $gioco)
    {
        $paginaGioco .= "<div class=\"pannelloVideogioco blur\">";
        $img = $gioco['immagine'];
        $paginaGioco .= "<img src=\"assets/game-covers/$img\" alt=\"\" class=\"copertinaVideogioco\">";
        $titolo = $gioco['titolo'];
        $paginaGioco .= "<h1><span lang=\"en\">$titolo</span></h1>";
        $paginaGioco .= "</div>";
        $paginaGioco .= "</div>";
        $paginaGioco .= "<div class=\"videogiocoPegiEAcquisto\">";
        $paginaGioco .= "<div class=\"videogiocoPegi\">";
        $pegi = $gioco['pegi'];
        $paginaGioco .= "<p><span lang=\"en\">PEGI</span> $pegi</p>";
        $paginaGioco .= "<img src=\"assets/PEGI_$pegi.svg\" alt=\"\">";
        $paginaGioco .= "</div>";
        $paginaGioco .= "<div class=\"videogiocoAcquisto\">";
        $paginaGioco .= "<a role=\"button\" href=\"videogioco.php?codice=$codice&acquisto=1\">Acquisto singolo</a>";
        $costo = $gioco['prezzo'];
        $paginaGioco .= "<p>Costo: $costo €</p>";
        $paginaGioco .= "[messaggio]";
        $paginaGioco .= "</div>";
        $paginaGioco .= "</div>";
        $paginaGioco .= "<h2>Dettagli</h2>";
        $paginaGioco .= "<div class=\"boxDescrizioneECategorie\">";
        $paginaGioco .= "<div class=\"boxDescrizioneEInfo\">";
        $descrizione = $gioco['descrizione'];
        $paginaGioco .= "<p><strong>Descrizione:</strong> <span lang=\"en\">$descrizione</span></p>";
        $paginaGioco .= "<div class=\"infoVideogioco\">";
        $produttore = $gioco['casaSviluppatrice'];
        $paginaGioco .= "<p><strong>Produttore:</strong> <span lang=\"en\">$produttore</span></p>";
        $dataUscita = $gioco['dataUscita'];
        $paginaGioco .= "<p><strong>Data Uscita: </strong>$dataUscita</p>";
        $paginaGioco .= "</div>";
        $paginaGioco .= "</div>";
        $paginaGioco .= "<div class=\"boxCategorie\">";
        $paginaGioco .= "<dl>";
        $paginaGioco .= "<dt>Categorie:</dt>";
        $listaCategorie = "<dd>";
        foreach($categorie as $categoria)
        {
            $cat = $categoria['categoria'];
            $listaCategorie .= $cat;
            $listaCategorie .= ",";
        }
        $listaCategorie = substr($listaCategorie, 0, -1);
        $listaCategorie .= "</dd>";
        $paginaGioco .= $listaCategorie;
        $paginaGioco .= "<dt>Piattaforma:</dt>";
        $listaPiattaforme = "<dd>";
        foreach($piattaforme as $piattaforma)
        {
            $piat = $piattaforma['piattaforma'];
            $listaPiattaforme .= $piat;
            $listaPiattaforme .= ",";
        }
        $listaPiattaforme = substr($listaPiattaforme, 0, -1);
        $listaPiattaforme .= "</dd>";
        $paginaGioco .= $listaPiattaforme;
        $paginaGioco .= "<dt>Abbonamenti:</dt>";
        $listaAbbonamenti = "<dd>";
        foreach($abbonamenti as $abbonamento)
        {
            $abb = $abbonamento['abbonamento'];
            $listaAbbonamenti .= $abb;
            $listaAbbonamenti .= ",";
        }
        $listaAbbonamenti = substr($listaAbbonamenti, 0, -1);
        $listaAbbonamenti .= "</dd>";
        $paginaGioco .= $listaAbbonamenti;
        $paginaGioco .= "</dl>";
        $paginaGioco .= "</div>";
    }
    $paginaGioco .= "</div>";
}
else
	//in fase di produzione rimuovere $connessioneOK
	$paginaGioco = $connectionOK ."<p>I sistemi sono momentaneamente fuori servizio, ci scusiamo per il disagio.</p>";

$paginaHTML = str_replace('[paginaGioco]', $paginaGioco, $paginaHTML);

if(isset($_GET['acquisto'])){
    $gioco = ($connection->getGiocoByCodice($codice)[0]);
    if (!isset($_SESSION['username']))
        $paginaHTML = str_replace("[messaggio]", "Per acquistare un videogioco bisogna registrarsi", $paginaHTML);
    else
    {
        if($connection->findAcquisto($_SESSION['username'],$codice))
            $paginaHTML = str_replace("[messaggio]", "Videogioco già acquistato", $paginaHTML);
        else
        {
            $result = $connection->acquistaGioco($_SESSION['username'], $codice, $costo);
            header("Location: /TecWeb-Project/acquistoCompletato.php");
        }
    }
}

$paginaHTML = str_replace("[messaggio]", "", $paginaHTML);
echo $paginaHTML;
?>