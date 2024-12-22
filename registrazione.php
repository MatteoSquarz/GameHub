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

$connessione = new DBAccess();
//$connessioneOK = $connessione->openDBConnection();

if (isset($_POST['registrati'])) {
	$messaggiPerForm .= "<ul>";

	$nome = $connessione->pulisciInput($_POST['nome']);
	if(!preg_match("/^[A-Za-z\ ]{2,}$/",$nome))
		$messaggiPerForm .= "<li>Il nome non può contenere numeri o caratteri speciali, almeno 2 caratteri</li>";

    $cognome = $connessione->pulisciInput($_POST['cognome']);
    if(!preg_match("/^[A-Za-z\ \']{2,}$/",$cognome))
        $messaggiPerForm .= "<li>Il cognome non può contenere numeri o caratteri speciali, almeno 2 caratteri</li>";

	$dataNascita = $connessione->pulisciInput($_POST['dataNascita']);

	$email = $connessione->pulisciInput($_POST['email']);
	if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/",$email))
		$messaggiPerForm .= "<li>L'email è nel formato text@text.text</li>";

    $username = $connessione->pulisciInput($_POST['username']);
	if(!preg_match("/^[A-Za-z0-9]{2,}$/",$username))
        $messaggiPerForm .= "<li>Lo username contiene solo lettere o numeri, almeno 2 caratteri</li>";

    $password = $connessione->pulisciInput($_POST['password']);
	if(!preg_match("/^[A-Za-z0-9\!\@\#\%]{8,}$/",$password))
        $messaggiPerForm .= "<li>La password contiene solo numeri lettere o i caratteri !@#%, almeno 8 caratteri</li>";

	$messaggiPerForm .= "</ul>";

	if($messaggiPerForm == ""){

		if($connessioneOK == true)
		{
			/*
			$nuovoUtente = $connection->insertNewUser("jesus","1234568","fabio","rossi","2001/12/25","lamiamail@gmail.com","null");
			if($nuovoUtente)
				echo "Inserito con successo <br>";
			*/
		}
	}
}

$paginaHTML = str_replace('[messaggiForm]', $messaggiPerForm, $paginaHTML);
echo $paginaHTML;
?>