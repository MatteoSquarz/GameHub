<?php
namespace DB;

class DBAccess{
    private const HOST = 'mariadb';
    private const DB_NAME = 'my_database';
    private const USERNAME = 'my_user';
    private const PASSWORD = 'my_password';

    private $connection;

	public function openDBConnection() {
		
		//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT)
		//try{
			//$this->connection = mysqli_connect(DBAccess::HOST_DB, DBAccess::USERNAME, DBAccess::PASSWORD, DBAccess::DATABASE_NAME);
			//fare query
		//}
		//catch(mysqli_sql_exception $e){
			//$errore = $e->getMessage()
		//}
		mysqli_report(MYSQLI_REPORT_ERROR);

		$this->connection = mysqli_connect(DBAccess::HOST, DBAccess::USERNAME, DBAccess::PASSWORD, DBAccess::DB_NAME);
		
		//solo per fase di debug
		return mysqli_connect_error();

		//produzione
		/*if(mysqli_connect_errno()){
			return false;
		} else {
			return true;
		}*/
		
	}

	public function closeConnection() {
		mysqli_close($this->connection);
	}

    function pulisciInput($value){
 	    // elimina gli spazi
 	    $value = trim($value);
 	    // rimuove tag html (non sempre è una buona idea!) 
  	    $value = strip_tags($value);
  	    // converte i caratteri speciali in entità html (ex. &lt;)
	    $value = htmlentities($value);
  	    return $value;
    }

	public function insertNewUser($username, $password, $nome, $cognome, $nascita, $email, $abbonamento) {
		$queryInsUtente = "INSERT INTO Utenti (username, password) VALUES (\"$username\", \"$password\")";
		$queryInsUser = "INSERT INTO Users (username, nome, cognome, dataNascita, email, abbonamentoAttuale) VALUES (\"$username\", \"$nome\", \"$cognome\", \"$nascita\", \"$email\", \"$abbonamento\")";
		
		$queryInsUtenteRes = mysqli_query($this->connection, $queryInsUtente) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
		if(mysqli_affected_rows($this->connection) > 0)
		{
			$queryInsUserRes = mysqli_query($this->connection, $queryInsUser) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
			if(mysqli_affected_rows($this->connection) > 0)
				return true;
			else
				return false;
		}
		else
			return false;
	}
}

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