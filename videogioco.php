<?php
require_once "templatedbConnection.php";
use DB\DBAccess;

$paginaHTML = file_get_contents('template/videogioco.html');

$menuLoginProfilo = "<li class=\"login\"><a href=\"login.php\">Accedi</a></li>";
session_start();
if (isset($_SESSION['username'])) 
    $menuLoginProfilo = "<li class=\"profile\"><a href=\"profilo.php\">Profilo</a></li>";

$paginaHTML = str_replace('[loginProfilo]', $menuLoginProfilo, $paginaHTML);

$connection = new DBAccess();
$connectionOK = false;

$gioco = null;
$categorie = "";
$piattaforme = "";
$paginaGioco = "";
$costo; 
$codice = $_GET['codice'];

try{
    $connectionOK = $connection->openDBConnection();
    if($connectionOK){
        if($connection->getGiocoByCodice($codice)){
            $gioco = $connection->getGiocoByCodice($codice)[0];
            $categorie = $connection->getCategoriaByCodiceGioco($codice);
            $piattaforme = $connection->getPiattaformaByCodiceGioco($codice);
            $abbonamenti = $connection->getAbbonamentoByCodiceGioco($codice);
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

if($gioco){  //se il gioco esiste
    $paginaGioco .= "<div class=\"backgroundPannelloVideogioco\">";
    $paginaGioco .= "<div class=\"pannelloVideogioco blur\">";
    $img = $gioco['immagine'];
    $paginaGioco .= "<img src=\"assets/game-covers/$img\" alt=\"\" class=\"copertinaVideogioco\">";
    $titolo = $gioco['titolo'];
    $titolo_pagina = strip_tags($titolo);
    $paginaGioco .= "<h1>$titolo</h1>";
    $paginaGioco .= "</div>";
    $paginaGioco .= "</div>";
    $paginaGioco .= "<div class=\"videogiocoPegiEAcquisto\">";
    $paginaGioco .= "<div class=\"videogiocoPegi\">";
    $pegi = $gioco['pegi'];
    $paginaGioco .= "<p><span lang=\"en\">PEGI</span> $pegi</p>";
    $paginaGioco .= "<img src=\"assets/PEGI_$pegi.svg\" alt=\"\">";
    $paginaGioco .= "</div>";
    $paginaGioco .= "<div class=\"videogiocoAcquisto\">";
    $paginaGioco .= "<a role=\"button\" class=\"buttonAcquistaVideogioco\" href=\"videogioco.php?codice=$codice&acquisto=1\">Acquisto singolo</a>";
    $costo = $gioco['prezzo'];
    $paginaGioco .= "<p>Costo: $costo";
    $paginaGioco .= "€</p>";
    $paginaGioco .= "</div>";
    $paginaGioco .= "</div>";
    $paginaGioco .= "[messaggio]";
    $paginaGioco .= "<h2>Dettagli</h2>";
    $paginaGioco .= "<div class=\"boxDescrizioneECategorie\">";
    $paginaGioco .= "<div class=\"boxDescrizioneEInfo\">";
    $descrizione = $gioco['descrizione'];
    $paginaGioco .= "<p><strong>Descrizione: </strong>$descrizione</p>";
    $paginaGioco .= "<div class=\"infoVideogioco\">";
    $produttore = $gioco['casaSviluppatrice'];
    $paginaGioco .= "<p><strong>Produttore: </strong>$produttore</p>";
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
        $listaCategorie .= ", ";
    }
    $listaCategorie = substr($listaCategorie, 0, -2);
    $listaCategorie .= "</dd>";
    $paginaGioco .= $listaCategorie;
    $paginaGioco .= "<dt>Piattaforma:</dt>";
    $listaPiattaforme = "<dd>";
    foreach($piattaforme as $piattaforma)
    {
        $piat = $piattaforma['piattaforma'];
        $listaPiattaforme .= $piat;
        $listaPiattaforme .= ", ";
    }
    $listaPiattaforme = substr($listaPiattaforme, 0, -2);
    $listaPiattaforme .= "</dd>";
    $paginaGioco .= $listaPiattaforme;
    $paginaGioco .= "<dt>Abbonamenti:</dt>";
    $listaAbbonamenti = "<dd>";
    foreach($abbonamenti as $abbonamento)
    {
        $abb = $abbonamento['abbonamento'];
        $listaAbbonamenti .= $abb;
        $listaAbbonamenti .= ", ";
    }
    $listaAbbonamenti = substr($listaAbbonamenti, 0, -2);
    $listaAbbonamenti .= "</dd>";
    $paginaGioco .= $listaAbbonamenti;
    $paginaGioco .= "</dl>";
    $paginaGioco .= "</div>";
    $paginaGioco .= "</div>";
}
else  //se il gioco non esiste
    header("Location: 404.php");

$paginaHTML = str_replace('[titoloPagina]', $titolo_pagina, $paginaHTML);
$paginaHTML = str_replace('[videogioco]', $titolo, $paginaHTML);
$paginaHTML = str_replace('[paginaGioco]', $paginaGioco, $paginaHTML);

if(isset($_GET['acquisto'])){
    if (!isset($_SESSION['username']))  //se non è loggato
        $paginaHTML = str_replace("[messaggio]", "<p class=\"itemCentered warningAcquisto\">Si prega di effettuare l'accesso prima di acquistare</p>", $paginaHTML);
    else{   //se è loggato
        try{
            $connectionOK = $connection->openDBConnection();
            if($connectionOK){
                if(!$connection->findAcquisto($_SESSION['username'],$codice)){   //se l'utente non ha già acquistato il gioco
                    $result = $connection->acquistaGioco($_SESSION['username'], $codice, $costo);
                    header("Location: acquistoCompletato.php");
                }
                else   //se l'utente ha già acquistato il gioco
                    $paginaHTML = str_replace("[messaggio]", "<p class=\"itemCentered warningAcquisto\">Hai già acquistato questo videogioco</p>", $paginaHTML);
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
}

$paginaHTML = str_replace("[messaggio]", "", $paginaHTML);
echo $paginaHTML;
?>