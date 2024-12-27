<?php
$paginaHTML = file_get_contents('index.html');
//$menuLoginProfilo = $_SESSION['username'];
$menuLoginProfilo = "<li><a href=\"login.php\">Accedi</a></li>";
session_start();
if (!empty($_SESSION)) 
    $menuLoginProfilo = "<li><a href=\"profilo.php\">Profilo</a></li>";

$paginaHTML = str_replace('[loginProfilo]', $menuLoginProfilo, $paginaHTML);
echo $paginaHTML;
?>