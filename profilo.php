<?php
$paginaHTML = file_get_contents('profilo.html');
//$menuLoginProfilo = $_SESSION['username'];
$menuLoginProfilo = "<li class=\"login\"><a href=\"login.php\">Accedi</a></li>";
session_start();
if (!empty($_SESSION)) 
    $menuLoginProfilo = "<li id=\"currentMenu\" class=\"profile\">Profilo</li>";

$paginaHTML = str_replace('[loginProfilo]', $menuLoginProfilo, $paginaHTML);
echo $paginaHTML;
?>