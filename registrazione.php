<?php
require_once "templatedbConnection.php";
use DB\DBAccess;

$paginaHTML = file_get_contents('template/registrazione.html');

function pulisciInput($value){
    $value = trim($value);
    $value = strip_tags($value);
    $value = htmlentities($value);
    return $value;
}

function pulisciCognome($value){
    $value = trim($value);
    $value = strip_tags($value);
    return $value;
}

$messaggiPerForm = "";
$erroreNome = "";
$erroreCognome = "";
$erroreDataNascita = "";
$erroreEmail = "";
$erroreUsername = "";
$errorePassword = "";

$nome = "";
$cognome = "";
$dataNascita = "";
$email = "";
$username = "";
$password = "";

$connection = new DBAccess();
$connectionOK = false;

if (isset($_POST['registrati'])) {
	$nome = pulisciInput($_POST['nome']);
	$cognome = pulisciCognome($_POST['cognome']);
	$dataNascita = pulisciInput($_POST['dataNascita']);
	$email = pulisciInput($_POST['email']);
	$username = pulisciInput($_POST['username']);
	$password = pulisciInput($_POST['password']);
	
	if(strlen($nome) == 0)
		$erroreNome .= "<strong class='errorFormRegistrazione'>Inserire il nome</strong>";
	else if(!preg_match("/^[A-Za-z\ ]{2,20}$/",$nome))
		$erroreNome .= "<strong class='errorFormRegistrazione'>Il nome non può contenere numeri o caratteri speciali, la lunghezza minima è di almeno 2 caratteri e massimo 20</strong>";
    
	if(strlen($cognome) == 0)
		$erroreCognome .= "<strong class='errorFormRegistrazione'>Inserire il cognome</strong>";
	else if(!preg_match("/^[a-zA-Z\ \']{2,20}$/",$cognome))
        $erroreCognome .= "<strong class='errorFormRegistrazione'>Il cognome non può contenere numeri o caratteri speciali, la lunghezza minima è di almeno 2 caratteri e massimo 20</strong>";

	if(strlen($dataNascita) == 0)
        $erroreDataNascita .= "<strong class='errorFormRegistrazione'>Inserire la data di nascita</strong>";
	
	if(strlen($email) == 0)
		$erroreEmail .= "<strong class='errorFormRegistrazione'>Inserire l'email</strong>";
	else if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/",$email))
		$erroreEmail .= "<strong class='errorFormRegistrazione'>Formato dell'email non corretto</strong>";
    
	if(strlen($username) == 0)
		$erroreUsername .= "<strong class='errorFormRegistrazione'>Inserire lo username</strong>";
	else if(!preg_match("/^[A-Za-z0-9]{2,20}$/",$username))
        $erroreUsername .= "<strong class='errorFormRegistrazione'>Lo username contiene solo lettere o numeri, la lunghezza minima è di almeno 2 caratteri e massimo 20</strong>";
   
	if(strlen($password) == 0)
		$errorePassword .= "<strong class='errorFormRegistrazione'>Inserire la password</strong>";
	else if(!preg_match("/^[A-Za-z0-9\!\@\#\%]{8,20}$/",$password))
        $errorePassword .= "<strong class='errorFormRegistrazione'>La password contiene solo numeri lettere o i caratteri !@#%, la lunghezza minima è di almeno 8 caratteri e massimo 20</strong>";

	$errori = $erroreNome . $erroreCognome . $erroreDataNascita . $erroreEmail . $erroreUsername . $errorePassword;
	if($errori == ""){  //se non ci sono errori
		try{
			$connectionOK = $connection->openDBConnection();
			if($connectionOK){
				$esistente = $connection->getUtente($username);
				if($esistente == null){  //se non esiste già un utente con quel username
					$nuovoUtente = $connection->insertNewUser($username,$password,$nome,$cognome,$dataNascita,$email);
					if($nuovoUtente){
						session_start();
						$_SESSION["registrazione"] = 1;
						header("Location: login.php");  //reindirizza alla pagina di login
					}
				}
				else   //se esiste già un utente con quel username do un errore
					$messaggiPerForm = "<span class='itemCentered errorUsernameFormRegistrazione'>Username già utilizzato, si prega di usarne un altro</span>";
			}
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
	}
}

$paginaHTML = str_replace('[erroreNome]', $erroreNome, $paginaHTML);
$paginaHTML = str_replace('[erroreCognome]', $erroreCognome, $paginaHTML);
$paginaHTML = str_replace('[erroreDataNascita]', $erroreDataNascita, $paginaHTML);
$paginaHTML = str_replace('[erroreEmail]', $erroreEmail, $paginaHTML);
$paginaHTML = str_replace('[erroreUsername]', $erroreUsername, $paginaHTML);
$paginaHTML = str_replace('[errorePassword]', $errorePassword, $paginaHTML);
$paginaHTML = str_replace('[messaggiForm]', $messaggiPerForm, $paginaHTML);
echo $paginaHTML;
?>