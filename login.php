<?php
require_once "templatedbConnection.php";
use DB\DBAccess;

$paginaHTML = file_get_contents('login.html');

$messaggiPerForm = "";

$username = "";
$password = "";

$connessione = new DBAccess();
//$connessioneOK = $connessione->openDBConnection();

if (isset($_POST['accedi'])) {
	$messaggiPerForm .= "<ul>";

    $username = $connessione->pulisciInput($_POST['username']);
	if(!preg_match("/^[a-zA-Z0-9]{2,}$/",$username))
        $messaggiPerForm .= "<li>Non è sicuramente uno username</li>";

    $password = $connessione->pulisciInput($_POST['password']);
	if(!preg_match("/^[A-Za-z0-9\!\@\#\%]{8,}$/",$password))
        $messaggiPerForm .= "<li>Non è sicuramente una password</li>";

	$messaggiPerForm .= "</ul>";

	if($messaggiPerForm == ""){

		if($connessioneOK == true)
		{
			/*
            $autenticazione = $connection->autenticaUtente("admin","admin");
            switch($autenticazione)
            {
	            case "no result":
		            echo "La query non ha prodotto risultati <br>";
	            break;
	            case "no user":
		            echo "Nessun username corrisponde <br>";
	            break;
	            case "authenticated":
		            echo "Utene autenticato <br>";
	            break;
	            case "not authenticated":
		            echo "Utene non autenticato <br>";
	            break;
            }
            */
		}
	}
}

$paginaHTML = str_replace('[messaggiForm]', $messaggiPerForm, $paginaHTML);
echo $paginaHTML;
?>