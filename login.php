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
	$messaggiPerForm .= "<ul class=\"errorLogin\">";
	
    $username = $connessione->pulisciInput($_POST['username']);
	/*if(!preg_match("/^[a-zA-Z0-9]{2,}$/",$username))
        $messaggiPerForm .= "<li>Non è sicuramente uno username</li>";*/

    $password = $connessione->pulisciInput($_POST['password']);
	/*if(!preg_match("/^[A-Za-z0-9\!\@\#\%]{4,}$/",$password))
        $messaggiPerForm .= "<li>Non è sicuramente una password</li>";*/
	
	
	if($connessioneOK == NULL){
		$autenticazione = $connessione->autenticaUtente($username,$password);
		if($connessione->autenticaUtente($username,$password)){
			session_start();
			if(empty($_SESSION)){
				$_SESSION["username"] = $username;
			}
			else{
				unset($_SESSION);
				$_SESSION["username"] = $username;
			}
			header("Location: /TecWeb-project/index.php");	
		}
		elseif($connessione->autenticaAdmin($username,$password)){
			session_start();
			if(empty($_SESSION)){
				$_SESSION["username"] = $username;
			}
			else{
				unset($_SESSION);
				$_SESSION["username"] = $username;
			}
			header("Location: /TecWeb-project/index.php");
		}
		else
			$messaggiPerForm .= "<li>Username e/o password errati</li>";
	}
	$messaggiPerForm .= "</ul>";
}

$paginaHTML = str_replace('[messaggiForm]', $messaggiPerForm, $paginaHTML);
echo $paginaHTML;
?>