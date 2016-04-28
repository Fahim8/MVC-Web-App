<?php
class FilmDetailsController {
	private $slimApp;
	private $model;
	private $requestBody;
	public function __construct($model, $action = null, $slimApp, $parameteres = null) {
		$this->model = $model;
		$this->slimApp = $slimApp;
		$this->requestBody = json_decode ( $this->slimApp->request->getBody (), true ); // this must contain the representation of the new user
		
		if (! empty ( $parameteres ["id"] ))
			$id = $parameteres ["id"];
			
		switch ($action) {
			case ACTION_GET_FILM_DETAILS :
				$this->getFilmDetails ( $id );
				break;
			case ACTION_GET_FILMS_DETAILS :
				$this->getFilmsDetails  ();
				break;
	
			case ACTION_CREATE_FILM_DETAILS :
				$this->createNewFilmDetails( $this->requestBody );
				break;
				
			case ACTION_UPDATE_FILM_DETAILS :
				$this->updateFilmDetails ( $id, $this->requestBody );
				break;
				
			case ACTION_DELETE_FILM_DETAILS :
				$this->deleteFilmDetails ( $id );
				break;
				
			case ACTION_SEARCH_FILM_DETAILS :
				$string = $parameteres ["searchString"];
				$this->searchFilmsDetails ( $string );
				break;
	
			case null :
				$this->slimApp->response ()->setStatus ( HTTPSTATUS_BADREQUEST );
				$Message = array (
						GENERAL_MESSAGE_LABEL => GENERAL_CLIENT_ERROR 
				);
				$this->model->apiResponse = $Message;
				break;
		}
	}
	private function getFilmsDetails() {
		$answer = $this->model->getFilmsDetails ();
		if ($answer != null) {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$this->model->apiResponse = $answer;
		} else {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_NOCONTENT );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_NOCONTENT_MESSAGE 
			);
			$this->model->apiResponse = $Message;
		}
	}
	private function getFilmDetails($filmDetailsID) {
		$answer = $this->model->getFilmDetails ( $filmDetailsID );
		if ($answer != null) {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$this->model->apiResponse = $answer;
		} else {
			
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_NOCONTENT );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_NOCONTENT_MESSAGE 
			);
			$this->model->apiResponse = $Message;
		}
	}
	private function createNewFilmDetails($newFilmDetails) 
	{
		if ($newID = $this->model->createNewFilmDetails ( $newFilmDetails )) 
		{
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_CREATED );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_RESOURCE_CREATED,
					"id" => "$newID" 
			);
			$this->model->apiResponse = $Message;
		}
		else 
		{
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_BADREQUEST );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_INVALIDBODY 
			);
			$this->model->apiResponse = $Message;
		}
	}
	
	
	private function updateFilmDetails($filmDetailsID, $newFilmDetailsName) {
		if ($this->model->updateFilmDetails ( $filmDetailsID, $newFilmDetailsName )) {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_RESOURCE_UPDATED,
					"updatedID" => "$filmDetailsID" 
			);
			$this->model->apiResponse = $Message;
		} else {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_BADREQUEST );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_INVALIDBODY 
			);
			$this->model->apiResponse = $Message;
		}
	}
	
	private function deleteFilmDetails($filmDetailsID) {
		if ($this->model->deleteFilmDetails ( $filmDetailsID )) {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_RESOURCE_DELETED 
			);
			$this->model->apiResponse = $Message;
		} else {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_BADREQUEST );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_ERROR_MESSAGE 
			);
			$this->model->apiResponse = $Message;
		}
	}
	
	private function searchFilmsDetails($string) {
		$answer = $this->model->searchFilmsDetails ( $string );
		if ($answer != null) {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$this->model->apiResponse = $answer;
		} else {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_NOCONTENT );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_NOCONTENT_MESSAGE 
			);
			
			$this->model->apiResponse = $Message;
		}
	}
}
?>