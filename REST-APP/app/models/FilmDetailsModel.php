<?php
require_once "DB/pdoDbManager.php";
require_once "DB/DAO/FilmsDetailsDAO.php";
require_once "Validation.php";
class FilmDetailsModel {
	private $FilmsDetailsDAO; // list of DAOs used by this model
	private $dbmanager; // dbmanager
	public $apiResponse; // api response
	private $validationSuite; // contains functions for validating inputs
	public function __construct() {
		$this->dbmanager = new pdoDbManager ();
		$this->FilmsDetailsDAO = new FilmsDetailsDAO( $this->dbmanager ); //FilmsDAO
		$this->dbmanager->openConnection ();
		$this->validationSuite = new Validation ();
	}
	public function getFilmsDetails() {
		return ($this->FilmsDetailsDAO->get ());
	}
	public function getFilmDetails($filmDetailsID) {
		if (is_numeric ( $filmDetailsID ))
			return ($this->FilmsDetailsDAO->get ( $filmDetailsID ));
		
		return false;
	}
	public function createNewFilmDetails($newFilmDetails) {		
		// compulsory values
		if (!empty ($newFilmDetails ["film_id"]) &&!empty($newFilmDetails ["rating"]) && !empty ($newFilmDetails ["length"]) && !empty ($newFilmDetails ["description"])) 
		{
			if (	($this->validationSuite->isLengthStringValid ( $newFilmDetails ["rating"], TABLE_FILM_RATING)) && 
					($this->validationSuite->isLengthStringValid ( $newFilmDetails ["length"], TABLE_FILM_LENGTH )) && 
					($this->validationSuite->isLengthIntValid ( $newFilmDetails ["description"], TABLE_FILM_DESCRIPTION ))) {
				try {
					$newId = $this->FilmsDetailsDAO->insert ( $newFilmDetails );
					return ($newId);
				} catch (Exception $e) {
						return false;
					}
			}
		}
		
		// if validation fails or insertion fails
		return (false);
	}
	
	
	public function updateFilmDetails($filmDetailsID, $newFilmDetailsRepresentation) {
		if (! empty ( $filmDetailsID ) && is_numeric ( $filmDetailsID )) {
			// compulsory values
			if (!empty ($newFilmDetailsRepresentation ["film_id"]) && !empty ($newFilmDetailsRepresentation ["rating"]) && 
				!empty ($newFilmDetailsRepresentation ["length"] ) && ! empty ($newFilmDetailsRepresentation ["description"] )) {
			
				if (	($this->validationSuite->isLengthIntValid ( $newFilmDetailsRepresentation ["rating"], TABLE_FILM_RATING )) &&
						($this->validationSuite->isLengthStringValid ( $newFilmDetailsRepresentation ["length"], TABLE_FILM_LENGTH )) && 
						($this->validationSuite->isLengthStringValid ( $newFilmDetailsRepresentation ["description"],TABLE_FILM_DESCRIPTION))) {
					try {
						$updatedRows = $this->SongsDAO->update ( $newFilmDetailsRepresentation, $filmDetailsID );
						if ($updatedRows > 0)
							return (true);
					} catch (Exception $e) {
						return false;
					}
				}
			}
		}
		return (false);
	}
	
	
	
	public function deleteFilmDetails($filmDetailsID) {
		if (is_numeric ( $filmDetailsID )) {
			$deletedRows = $this->FilmsDetailsDAO->delete ( $filmDetailsID );
			if ($deletedRows > 0)
				return (true);
		}
		return (false);
	}
	
	
	
	
	public function searchFilmsDetails($string) {
		if (! empty ( $string )) {
			$resultSet = $this->FilmsDetailsDAO->search ( $string );
			return $resultSet;
		}
		
		return false;
	}
	
	
	
	
	public function __destruct() {
		$this->FilmsDetailsDAO = null;
		$this->dbmanager->closeConnection ();
	}
}
?>