<?php
namespace DB;

class DBAccess{
    private const HOST = 'localhost';
    private const DB_NAME = 'prova';
    private const USERNAME = 'root';
    private const PASSWORD = '';

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

	public function getListAbbonamenti() {
		$query = "SELECT * from Abbonamento ORDER BY Livello ASC";

		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
		
		if(mysqli_num_rows($queryResult) == 0) {
			return null;
		} else {
			$result = array();
			while($row=mysqli_fetch_assoc($queryResult)){
				array_push($result, $row);
			}
			$queryResult->free();
			return $result;
		}
	}

	public function getListGiochi() {
		$query = "SELECT * from Videogioco";

		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
		
		if(mysqli_num_rows($queryResult) == 0) {
			return null;
		} else {
			$result = array();
			while($row=mysqli_fetch_assoc($queryResult)){
				array_push($result, $row);
			}
			$queryResult->free();
			return $result;
		}
	}

	public function getGiocoByCodice($codice){
		$query = "SELECT * from Videogioco WHERE codice = '$codice'";

		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));

		if(mysqli_num_rows($queryResult) == 0) {
			return null;
		} else {
			$result = array();
			while($row=mysqli_fetch_assoc($queryResult)){
				array_push($result, $row);
			}
			$queryResult->free();
			return $result;
		}
	}

	public function getCategoriaByCodiceGioco($codice){
		$query = "SELECT * from CategoriaVideogioco WHERE videogioco = '$codice'";

		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));

		if(mysqli_num_rows($queryResult) == 0) {
			return null;
		} else {
			$result = array();
			while($row=mysqli_fetch_assoc($queryResult)){
				array_push($result, $row);
			}
			$queryResult->free();
			return $result;
		}
	}

    public function getPiattaformaByCodiceGioco($codice){
		$query = "SELECT * from PiattaformaVideogioco WHERE videogioco = '$codice'";

		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));

		if(mysqli_num_rows($queryResult) == 0) {
			return null;
		} else {
			$result = array();
			while($row=mysqli_fetch_assoc($queryResult)){
				array_push($result, $row);
			}
			$queryResult->free();
			return $result;
		}
	}

    public function getAbbonamentoByCodiceGioco($codice){
		$query = "SELECT * from AbbonamentoVideogioco WHERE videogioco = '$codice'";

		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));

		if(mysqli_num_rows($queryResult) == 0) {
			return null;
		} else {
			$result = array();
			while($row=mysqli_fetch_assoc($queryResult)){
				array_push($result, $row);
			}
			$queryResult->free();
			return $result;
		}
	}

	public function getUtente($username){
		$query = "SELECT * from User WHERE username = '$username'";

		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));

		if(mysqli_num_rows($queryResult) == 0) {
			return null;
		} else {
			$result = array();
			while($row=mysqli_fetch_assoc($queryResult)){
				array_push($result, $row);
			}
			$queryResult->free();
			return $result;
		}
	}

	public function getAcquisti($username){
		$query = "SELECT * from Vendita WHERE utente = '$username'";

		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));

		if(mysqli_num_rows($queryResult) == 0) {
			return null;
		} else {
			$result = array();
			while($row=mysqli_fetch_assoc($queryResult)){
				array_push($result, $row);
			}
			$queryResult->free();
			return $result;
		}
	}

	public function getImmagineAbbonamento($abbonamento){
		$query = "SELECT * from Abbonamento WHERE nome = '$abbonamento'";

		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));

		if(mysqli_num_rows($queryResult) == 0) {
			return null;
		} else {
			$row=mysqli_fetch_assoc($queryResult);
			return $row['immagine'];
		}
	}

	public function pulisciInput($value){
		// elimina gli spazi
		$value = trim($value);
		// rimuove tag html (non sempre è una buona idea!) 
		$value = strip_tags($value);
		// converte i caratteri speciali in entità html (ex. &lt;)
	    $value = htmlentities($value);
		return $value;
    }

	public function autenticaUtente($username, $password){  //ritornare solo true o false 
		$query = "SELECT * from Utente";
		// $query = "SELECT * from User WHERE username = '$username' AND password = '$password'";
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
		
		$authenticated = "";
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
				$authenticated = "no user";
		}
		return $authenticated;
	}

	//fare metodo per autenticare admin
	// $query = "SELECT * from Admin WHERE username = '$username' AND password = '$password'";

	public function insertNewUser($username, $password, $nome, $cognome, $nascita, $email, $abbonamento) {
		$queryInsUtente = "INSERT INTO Utente (username, password) VALUES (\"$username\", \"$password\")";
		$queryInsUser = "INSERT INTO User (username, nome, cognome, dataNascita, email, abbonamentoAttuale) VALUES (\"$username\", \"$nome\", \"$cognome\", \"$nascita\", \"$email\", \"$abbonamento\")";
		
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

?>