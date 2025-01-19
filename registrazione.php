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

$messaggiPerForm = "";

$nome = "";
$cognome = "";
$dataNascita = "";
$email = "";
$username = "";
$password = "";

$connection = new DBAccess();
$connectionOK = false;

if (isset($_POST['registrati'])) {
	$messaggiPerForm .= "<ul class='itemCentered errorFormRegistrazione'>";

	$nome = pulisciInput($_POST['nome']);
	$cognome = pulisciInput($_POST['cognome']);
	$dataNascita = pulisciInput($_POST['dataNascita']);
	$email = pulisciInput($_POST['email']);
	$username = pulisciInput($_POST['username']);
	$password = pulisciInput($_POST['password']);
	
	if(!preg_match("/^[A-Za-z\ ]{2,}$/",$nome))
		$messaggiPerForm .= "<li>Il nome non può contenere numeri o caratteri speciali, la lunghezza minima è di almeno 2 caratteri</li>";
   
	if(!preg_match("/^[A-Za-z\ \']{2,}$/",$cognome))
        $messaggiPerForm .= "<li>Il cognome non può contenere numeri o caratteri speciali, la lunghezza minima è di almeno 2 caratteri</li>";
	
	if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/",$email))
		$messaggiPerForm .= "<li>Formato dell'email non corretto</li>";
    
	if(!preg_match("/^[A-Za-z0-9]{2,}$/",$username))
        $messaggiPerForm .= "<li>Lo username contiene solo lettere o numeri, la lunghezza minima è di almeno 2 caratteri</li>";
   
	if(!preg_match("/^[A-Za-z0-9\!\@\#\%]{8,}$/",$password))
        $messaggiPerForm .= "<li>La password contiene solo numeri lettere o i caratteri !@#%, la lunghezza minima è di almeno 8 caratteri</li>";

	$messaggiPerForm .= "</ul>";

	if($messaggiPerForm == "<ul class='itemCentered errorFormRegistrazione'></ul>"){
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
					$messaggiPerForm = "<span class='itemCentered errorFormRegistrazione'>Username già utilizzato, si prega di usarne un altro</span>";
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

$paginaHTML = str_replace('[messaggiForm]', $messaggiPerForm, $paginaHTML);
echo $paginaHTML;
?>