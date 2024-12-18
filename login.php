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

    public function autenticaUtente($username, $password){
		$query = "SELECT * from Utenti";

		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
		if(mysqli_num_rows($queryResult) == 0) {
			return "no result";
		} else {
			$result = array();
			while($row=mysqli_fetch_assoc($queryResult)){
				array_push($result, $row);
			}
			$queryResult->free();
		}

		foreach($result as $utente)
		{
			if($username == $utente['username'])
			{
				if($password == $utente['password'])
					return "authenticated";
				else
					return "not authenticated";
			}
			else
				return "no user";
		}
	}
}

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