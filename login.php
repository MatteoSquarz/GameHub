<?php
require_once "templatedbConnection.php";
use DB\DBAccess;

$paginaHTML = file_get_contents('login.html');

function pulisciInput($value){
    $value = trim($value);
    $value = strip_tags($value);
    $value = htmlentities($value);
    return $value;
}

session_start();
if (isset($_SESSION['registrazione']))
{
	$paginaHTML = str_replace('[registrazione]', "<p class='confermaRegistrazione'>Utente registrato con successo</p>", $paginaHTML);
	unset($_SESSION['registrazione']);
}
else
	$paginaHTML = str_replace('[registrazione]', "", $paginaHTML);

$messaggiPerForm = "";

$username = "";
$password = "";

$connection = new DBAccess();

if (isset($_POST['accedi'])) {
	$messaggiPerForm .= "<ul class=\"errorLogin\">";
	
	$username = pulisciInput($_POST['username']);
	$password = pulisciInput($_POST['password']);
	
	if(!preg_match("/^[A-Za-z0-9]{2,}$/",$username))
		$messaggiPerForm .= "<li>Caratteri non concessi nello username</li>";
	if(!preg_match("/^[A-Za-z0-9\!\@\#\%]{2,}$/",$password))
		$messaggiPerForm .= "<li>Caratteri non concessi nella password</li>";

	if($messaggiPerForm == "<ul class=\"errorLogin\">"){
		if($connection->openDBConnection()){
			if($connection->autenticaUtente($username,$password)){
				if(empty($_SESSION)){
					$_SESSION["username"] = $username;
				}
				else{
					unset($_SESSION);
					$_SESSION["username"] = $username;
				}
				header("Location: index.php");	
			}
			elseif($connection->autenticaAdmin($username,$password)){
				if(empty($_SESSION)){
					$_SESSION["username"] = $username;
				}
				else{
					unset($_SESSION);
					$_SESSION["username"] = $username;
				}
				header("Location: admin.php");
			}
			else{
				$messaggiPerForm .= "<li>Username e/o password errati</li>";
			}
			$connection->closeDBConnection();
		}
		else
			header("Location: 500.php");
	}

	$messaggiPerForm .= "</ul>";
}

$paginaHTML = str_replace('[messaggiForm]', $messaggiPerForm, $paginaHTML);
echo $paginaHTML;
?>