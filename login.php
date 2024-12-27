<?php
require_once "templatedbConnection.php";
use DB\DBAccess;

$paginaHTML = file_get_contents('login.html');

$messaggiPerForm = "";

$username = "";
$password = "";

$connessione = new DBAccess();
$connessioneOK = $connessione->openDBConnection();

if (isset($_POST['accedi'])) {
	$messaggiPerForm .= "<ul>";

    $username = $connessione->pulisciInput($_POST['username']);
	if(!preg_match("/^[a-zA-Z0-9]{2,}$/",$username))
        $messaggiPerForm .= "<li>Non è sicuramente uno username</li>";

    $password = $connessione->pulisciInput($_POST['password']);
	if(!preg_match("/^[A-Za-z0-9\!\@\#\%]{4,}$/",$password))
        $messaggiPerForm .= "<li>Non è sicuramente una password</li>";

	if($messaggiPerForm == "<ul>"){
		if($connessioneOK == NULL)
		{
            $autenticazione = $connessione->autenticaUtente($username,$password);
            switch($autenticazione)
            {
	            case "no result":
					$messaggiPerForm .= "<li>La query non ha prodotto risultati</li>";//pagina d'errore
	            break;
	            case "no user":
					$messaggiPerForm .= "<li>Nessun username corrisponde</li>";//si può accorpare con quella sotto: username e/o password errati
	            break;
	            case "not authenticated":
					$messaggiPerForm .= "<li>Utente non autenticato</li>";//si può accorpare con quella sopra: username e/o password errati
	            break;
				case "authenticated":
					session_start();
					if(empty($_SESSION))
					{
    					$_SESSION["username"] = $username;
					}
					else 
					{
						unset($_SESSION);
						$_SESSION["username"] = $username;
					}
					header("Location: /index.php");
  					exit();
	            break;
            }
		}
	}
	$messaggiPerForm .= "</ul>";
}

$paginaHTML = str_replace('[messaggiForm]', $messaggiPerForm, $paginaHTML);
echo $paginaHTML;
?>