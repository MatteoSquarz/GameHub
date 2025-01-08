<?php
require_once "templatedbConnection.php";
use DB\DBAccess;

$paginaHTML = file_get_contents('admin.html');

//fare un controllo se admin o no

session_start();
if(isset($_GET['logout'])){
    unset($_SESSION['username']);
    header("Location: /TecWeb-project/index.php");
  	exit();
}

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

    foreach($piattaforme as $piattaforma){
        $nome = $piattaforma['nome'];
        $listaPiattaforme .= "<div class=\"sceltaPiattaforma\">";
        $listaPiattaforme .= "<label for=\"$nome\">$nome </label>";
        $listaPiattaforme .= "<input type=\"checkbox\" id=\"$nome\" name=\"$nome\" value=\"$nome\" required>";
        $listaPiattaforme .= "</div>";
    }

    $count = 0;
    foreach($categorie as $categoria){
        if($count % 5 == 0) { //apre un nuovo gruppo di 5 categorie
            $listaCategorie .= "<div class=\"gruppoScelteCategoria\">";
        }
        $nome = $categoria['nome'];
        $listaCategorie .= "<div>";
        $listaCategorie .= "<label for='$nome'>$nome </label>";
        $listaCategorie .= "<input type='checkbox' id='$nome' name='$nome' value='$nome' required>";
        $listaCategorie .= "</div>";
        if($count % 5 == 4) { //chiude il gruppo di 5 categorie
            $listaCategorie .= "</div>";
        }
        $count++;
    }
    $count--;
    if($count % 5 != 4) { //chiude l'ultimo gruppo di 5 categorie
        $listaCategorie .= "</div>";
    }


}

$paginaHTML = str_replace('[listaAbbonamenti]', $listaAbbonamenti, $paginaHTML);
$paginaHTML = str_replace('[listaCategorie]', $listaCategorie, $paginaHTML);
$paginaHTML = str_replace('[listaPiattaforme]', $listaPiattaforme, $paginaHTML);

echo $paginaHTML;
?>