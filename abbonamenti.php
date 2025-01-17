<?php
require_once "templatedbConnection.php";
use DB\DBAccess;

$paginaHTML = file_get_contents('template/abbonamenti.html');

$menuLoginProfilo = "<li class=\"login\"><a href=\"login.php\">Accedi</a></li>";
session_start();
if (isset($_SESSION['username'])) 
    $menuLoginProfilo = "<li class=\"profile\"><a href=\"profilo.php\">Profilo</a></li>";

$paginaHTML = str_replace('[loginProfilo]', $menuLoginProfilo, $paginaHTML);

$connection = new DBAccess();
$connectionOK = false;

$abbonamenti = "";
$listaAbbonamenti = "";

try{
    $connectionOK = $connection->openDBConnection();
    if($connectionOK)
        $abbonamenti = $connection->getListAbbonamenti();
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
    $listaAbbonamenti .= "<p>Non ci sono abbonamenti da visualizzare</p>";

$paginaHTML = str_replace('[listaAbbonamenti]', $listaAbbonamenti, $paginaHTML);

if(isset($_GET['abbonamento']))
{
    $abb = $_GET['abbonamento'];
    if (!isset($_SESSION['username']))   //se utente non è loggato
        $paginaHTML = str_replace("[messaggio$abb]", "<p class=\"warningAbbonamento\">Si prega di effettuare il login prima di abbonarsi</p>", $paginaHTML);
    else{   //se utente è loggato
        try{
            $connectionOK = $connection->openDBConnection();
            if($connectionOK){
                $utente = ($connection->getUtente($_SESSION['username'])[0]);
                if($utente['abbonamentoAttuale'] == NULL){  //se non ha già un abbonamento attivo
                    $result = $connection->acquistaAbbonamento($utente['username'], $abb);
                    header("Location: acquistoCompletato.php");
                }
                else   //se ha un abbonamento attivo
                    $paginaHTML = str_replace("[messaggio$abb]", "<p class=\"warningAbbonamento\">Sembra che tu abbia già un abbonamento attivo, recati sulla pagina profilo e disdici il tuo attuale abbonamento</p>", $paginaHTML);
            }
            else
                header("Location: 500.php");
        }
        catch(mysqli_sql_exception $e){  //se c'è un errore a livello database
            header("Location: 500.php");
        }
        finally{  //chiudo la connessione in ogni caso
            if($connectionOK)
                $connection->closeDBConnection();
        }
    }
}

$paginaHTML = str_replace('[messaggioBase]', "", $paginaHTML);
$paginaHTML = str_replace('[messaggioDeluxe]', "", $paginaHTML);
$paginaHTML = str_replace('[messaggioPremium]', "", $paginaHTML);
echo $paginaHTML;
?>