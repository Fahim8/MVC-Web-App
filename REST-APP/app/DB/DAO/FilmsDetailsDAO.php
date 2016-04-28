<?php
class FilmsDetailsDAO {
	private $dbManager;
	function FilmsDetailsDAO($DBMngr) {
		$this->dbManager = $DBMngr;
	}
	
	public function get($id = null) {
		$sql = "SELECT * ";
		$sql .= "FROM film_details ";
		if ($id != null)
			$sql .= "WHERE film_details.details_id=? ";
		$sql .= "ORDER BY film_details.length ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $id, $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	
	public function insert($parametersArray) {
		// insertion assumes that all the required parameters are defined and set
		$sql = "INSERT INTO film_details (film_id, rating, length, description) ";
		$sql .= "VALUES (?,?,?,?) ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray ["film_id"], $this->dbManager->INT_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray ["rating"], $this->dbManager->INT_TYPE );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray ["length"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["description"], $this->dbManager->STRING_TYPE );
		//$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["fid"], $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		
		return ($this->dbManager->getLastInsertedID ());
	}

	
	public function update($parametersArray, $filmDetailsID) {
		// /create an UPDATE sql statement (reads the parametersArray - this contains the fields submitted in the HTML5 form)
		$sql = "UPDATE film_details SET film_id = ?, rating = ?, length = ?, description = ? WHERE details_id = ?";
		
		$this->dbManager->openConnection ();
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray ["film_id"], PDO::PARAM_INT );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray ["rating"], PDO::PARAM_INT );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray ["length"], PDO::PARAM_STR );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["description"], PDO::PARAM_STR );
		$this->dbManager->bindValue ( $stmt, 5, $filmDetailsID, PDO::PARAM_INT );
		$this->dbManager->executeQuery ( $stmt );
		
		//check for number of affected rows
		$rowCount = $this->dbManager->getNumberOfAffectedRows($stmt);
		return ($rowCount);
	}
	
	public function delete($filmDetailsID) {
		$sql = "DELETE FROM film_details ";
		$sql .= "WHERE film_details.details_id = ?";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $filmDetailsID, $this->dbManager->INT_TYPE );
		
		$this->dbManager->executeQuery ( $stmt );
		$rowCount = $this->dbManager->getNumberOfAffectedRows ( $stmt );
		return ($rowCount);
	}
	
	public function search($str) {
		$sql = "SELECT * ";
		$sql .= "FROM film_details ";
		$sql .= "WHERE film_details.length LIKE CONCAT('%', ?, '%') or film_details.description LIKE CONCAT('%', ?, '%')  ";
		$sql .= "ORDER BY film_details.length ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $str, $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $str, $this->dbManager->STRING_TYPE );
		
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	
}
?>