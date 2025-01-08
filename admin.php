<?php
require_once "templatedbConnection.php";
use DB\DBAccess;

$paginaHTML = file_get_contents('admin.html');

//fare un controllo se admin o no


$connection = new DBAccess();
$connectionOK = $connection->openDBConnection();

$abbonamenti = "";
$listaAbbonamenti = "";
$categorie = "";
$listaCategorie = "";
$piattaforme = "";
$listaPiattaforme = "";

if(!$connectionOK){
    $abbonamenti = $connection->getListAbbonamenti();
    $categorie = $connection->getListCategorie();
    $piattaforme = $connection->getListPiattaforme();
    $connection->closeDBConnection();


    foreach($abbonamenti as $abbonamento){
        $nome = $abbonamento['nome'];
        $listaAbbonamenti .= "<option value=\"$nome\">$nome</option>";
    }


}

$paginaHTML = str_replace('[listaAbbonamenti]', $listaAbbonamenti, $paginaHTML);