<?php
namespace DB;

class DBAccess{
    private const HOST = 'mariadb';
    private const DB_NAME = 'my_database';
    private const USERNAME = 'my_user';
    private const PASSWORD = 'my_password';

    private $connection;

	public function openDBConnection() {		
		mysqli_report(MYSQLI_REPORT_ERROR);

		$this->connection = mysqli_connect(DBAccess::HOST, DBAccess::USERNAME, DBAccess::PASSWORD, DBAccess::DB_NAME);

		if(mysqli_connect_errno()){
			return false;
		} else {
			return true;
		}
	}

	public function closeDBConnection() {
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

	public function getListCategorie() {
		$query = "SELECT * from Categoria";

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

	public function getListPiattaforme() {
		$query = "SELECT * from Piattaforma";

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
		$query = "SELECT * from Cliente WHERE username = '$username'";

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
		$query = "SELECT * from Vendita, Videogioco WHERE Vendita.videogioco = Videogioco.codice and utente = '$username'";

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

	public function autenticaUtente($username, $password){
		$query = "SELECT * from Utente WHERE username = '$username' AND password = '$password' AND username IN (SELECT username FROM Cliente)";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
		if(mysqli_num_rows($queryResult) == 1) {
			return true;
		} else {
			$queryResult->free();
			return false;
		}
	}

	public function autenticaAdmin($username, $password){ 
		$query = "SELECT * from Utente WHERE username = '$username' AND password = '$password' AND username IN (SELECT username FROM Admin)";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
		if(mysqli_num_rows($queryResult) == 1) {
			return true;
		} else {
			$queryResult->free();
			return false;
		}
	}

	public function insertNewUser($username, $password, $nome, $cognome, $nascita, $email) {
		$queryInsUtente = "INSERT INTO Utente (username, password) VALUES (\"$username\", \"$password\")";
		$queryInsUser = "INSERT INTO Cliente (username, nome, cognome, dataNascita, email, abbonamentoAttuale, dataInizio, dataFine) VALUES (\"$username\", \"$nome\", \"$cognome\", \"$nascita\", \"$email\", NULL, NULL, NULL)";
		
		$queryInsRes = mysqli_query($this->connection, $queryInsUtente) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
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

	public function disdiciAbbonamento($username){
		$query = "UPDATE Cliente SET abbonamentoAttuale = NULL, dataInizio = NULL, dataFine = NULL WHERE username = '$username'";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
		if(mysqli_affected_rows($this->connection) > 0)
			return true;
		else
			return false;
	}

	public function acquistaAbbonamento($username, $abbonamento){
		$dataInizio = date('Y-m-d');
		$timestamp = strtotime("+1 year");
		$dataFine = date('Y-m-d', $timestamp);
		$query = "UPDATE Cliente SET abbonamentoAttuale = '$abbonamento', dataInizio = '$dataInizio', dataFine = '$dataFine' WHERE username = '$username'";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
		if(mysqli_affected_rows($this->connection) > 0)
			return true;
		else
			return false;
	}

	public function findAcquisto($username, $codice){
		$query = "SELECT * from Vendita WHERE videogioco = '$codice' and utente = '$username'";

		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));

		if(mysqli_num_rows($queryResult) == 0) {
			return false;
		} else {
			return true;
		}
	}

	public function acquistaGioco($username, $codice, $costo){
		$data = date('Y-m-d');
		$query = "INSERT INTO Vendita (utente, data, totale, videogioco) VALUES (\"$username\", \"$data\", \"$costo\", \"$codice\")";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
		if(mysqli_affected_rows($this->connection) > 0)
			return true;
		else
			return false;
	}

	public function verifyAdmin($username){
		$query = "SELECT * FROM Admin WHERE username = '$username'";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
		if(mysqli_affected_rows($this->connection) > 0)
			return true;
		else
			return false;
	}

	public function insertGioco($codice, $titolo, $descrizione, $prezzo, $dataUscita, $pegi, $casaSviluppatrice, $immagine){
		$query = "INSERT INTO Videogioco (codice, titolo, descrizione, prezzo, dataUscita, pegi, casaSviluppatrice, immagine) VALUES (\"$codice\", \"$titolo\", \"$descrizione\", \"$prezzo\", \"$dataUscita\", \"$pegi\", \"$casaSviluppatrice\", \"$immagine\")";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
		if(mysqli_affected_rows($this->connection) > 0)
			return true;
		else
			return false;
	}

	public function rimuoviGioco($codice){
		$query = "DELETE FROM Videogioco where codice = '$codice'";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
		if(mysqli_affected_rows($this->connection) > 0)
			return true;
		else
			return false;
	}

	public function modificaPrezzoAbbonamento($abbonamento, $prezzo){
		$query = "UPDATE Abbonamento SET prezzo = '$prezzo' WHERE nome = '$abbonamento'";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
		if(mysqli_affected_rows($this->connection) > 0)
			return true;
		else
			return false;
	}

	public function insertCategorieGioco($codice, $categorie){
		$valori = "";
		foreach($categorie as $categoria)
			$valori .= "('$categoria', '$codice'), ";
		$valori = substr($valori, 0, -2);
		$query = "INSERT INTO CategoriaVideogioco (categoria, videogioco) VALUES $valori;";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
		if(mysqli_affected_rows($this->connection) > 0)
			return true;
		else
			return false;
	}

	public function insertPiattaformeGioco($codice, $piattaforme){
		$valori = "";
		foreach($piattaforme as $piattaforma)
			$valori .= "('$piattaforma', '$codice'), ";
		$valori = substr($valori, 0, -2);
		$query = "INSERT INTO PiattaformaVideogioco (piattaforma, videogioco) VALUES $valori;";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
		if(mysqli_affected_rows($this->connection) > 0)
			return true;
		else
			return false;
	}

	public function insertAbbonamentiGioco($codice, $abbonamento){
		$valori = "";
		switch($abbonamento)
		{
			case "Base":
				$valori = "('Base', '$codice'), ('Deluxe', '$codice'), ('Premium', '$codice');";
			break;
			case "Deluxe":
				$valori = "('Deluxe', '$codice'), ('Premium', '$codice');";
			break;
			case "Premium":
				$valori = "('Premium', '$codice');";
			break;
		}
		$query = "INSERT INTO AbbonamentoVideogioco (abbonamento, videogioco) VALUES $valori;";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in openDBConnection " . mysqli_error($this-> connection));
		if(mysqli_affected_rows($this->connection) > 0)
			return true;
		else
			return false;
	}

}

?>