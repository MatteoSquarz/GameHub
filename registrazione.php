<?php
require_once "templatedbConnection.php";
use DB\DBAccess;

$paginaHTML = file_get_contents('registrazione.html');

$messaggiPerForm = "";

$nome = "";
$cognome = "";
$dataNascita = "";
$email = "";
$username = "";
$password = "";

$connection = new DBAccess();

if (isset($_POST['registrati'])) {
	$messaggiPerForm .= "<ul class='itemCentered errorFormRegistrazione'>";

	if($connection->openDBConnection())
	{
		$nome = $connection->pulisciInput($_POST['nome']);
		$cognome = $connection->pulisciInput($_POST['cognome']);
		$dataNascita = $connection->pulisciInput($_POST['dataNascita']);
		$email = $connection->pulisciInput($_POST['email']);
		$username = $connection->pulisciInput($_POST['username']);
		$password = $connection->pulisciInput($_POST['password']);
		$connection->closeDBConnection();
	}
	else
		header("Location: /TecWeb-project/500.php");
	
	if(!preg_match("/^[A-Za-z\ ]{2,}$/",$nome))
		$messaggiPerForm .= "<li>Il nome non può contenere numeri o caratteri speciali, almeno 2 caratteri</li>";
   
    if(!preg_match("/^[A-Za-z\ \']{2,}$/",$cognome))
        $messaggiPerForm .= "<li>Il cognome non può contenere numeri o caratteri speciali, almeno 2 caratteri</li>";
	
	if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/",$email))
		$messaggiPerForm .= "<li>L'email è nel formato text@text.text</li>";
    
	if(!preg_match("/^[A-Za-z0-9]{2,}$/",$username))
        $messaggiPerForm .= "<li>Lo username contiene solo lettere o numeri, almeno 2 caratteri</li>";
   
	if(!preg_match("/^[A-Za-z0-9\!\@\#\%]{8,}$/",$password))
        $messaggiPerForm .= "<li>La password contiene solo numeri lettere o i caratteri !@#%, almeno 8 caratteri</li>";

	$messaggiPerForm .= "</ul>";

	if($messaggiPerForm == "<ul class='itemCentered errorFormRegistrazione'></ul>"){
		if($connection->openDBConnection())
		{
			$esistente = $connection->getUtente($username);
			$connection->closeDBConnection();
			if($esistente == null)
			{
				if($connection->openDBConnection())
				{
					$nuovoUtente = $connection->insertNewUser($username,$password,$nome,$cognome,$dataNascita,$email);
					$connection->closeDBConnection();
				}
				else
					header("Location: /TecWeb-project/500.php");

				if($nuovoUtente)
				{
					session_start();
					$_SESSION["registrazione"] = 1;
					header("Location: /TecWeb-project/login.php");
				}
			}
			else
				$messaggiPerForm = "<p>Username già utilizzato, si prega di usarne un altro</p>";				
		}
		else
			header("Location: /TecWeb-project/500.php");
	}
}

$paginaHTML = str_replace('[messaggiForm]', $messaggiPerForm, $paginaHTML);
echo $paginaHTML;
?>