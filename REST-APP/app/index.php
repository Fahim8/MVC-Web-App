<?php
require_once "../Slim/Slim.php";
Slim\Slim::registerAutoloader ();

$app = new \Slim\Slim (); // slim run-time object

require_once "conf/config.inc.php";

// route middleware for simple API authentication
function authenticate(\Slim\Route $route)
	{
		$app = \Slim\Slim::getInstance();
		$action = ACTION_AUTHENTICATE_USER;
		$parameters["username"] = $app->request->headers->get("username");
		$parameters["password"] = $app->request->headers->get("password");
		$mvc = new loadRunMVCComponents ( "AuthenticationModel", "AuthenticationController", "jsonView", $action, $app, $parameters );
		if ($mvc->model->apiResponse === false) 
		{
			$app->halt(401);
		}
	}
	
	
function checkType($app)
	{
		$viewType = $app->request->headers->get("Content-Type");
		
		if($viewType == RESPONSE_FORMAT_XML)
			$viewType = "xmlView";
		else
			$viewType = "jsonView";
		
		return $viewType;
	}

$app->map ( "/users(/:id)", function ($userID = null) use($app)
 {
	
	$httpMethod = $app->request->getMethod ();
	$action = null;
	$parameters ["id"] = $userID; // prepare parameters to be passed to the controller (example: ID)
	
	if (($userID == null) or is_numeric ( $userID )) {
		switch ($httpMethod) {
			case "GET" :
				if ($userID != null)
					$action = ACTION_GET_USER;
				else
					$action = ACTION_GET_USERS;
				break;
			case "POST" :
				$action = ACTION_CREATE_USER;
				break;
			case "PUT" :
				$action = ACTION_UPDATE_USER;	
				break;
			case "DELETE" :
				$action = ACTION_DELETE_USER;
				break;
			default :
		}
	}
	$viewType = checkType($app);
	return new loadRunMVCComponents ( "UserModel", "UserController", $viewType, $action, $app, $parameters );
} )->via ( "GET", "POST", "PUT", "DELETE" );


$app->map ( "/users/search(/:string)", function ($searchString = null) use($app) {
	$httpMethod = $app->request->getMethod ();
	$action = null;
	$parameters ["searchString"] = $searchString; // prepare parameters to be passed to the controller (example: ID)
	if (($searchString == null) or is_string( $searchString )) {
		switch ($httpMethod) {
			case "GET" :
				$action = ACTION_SEARCH_USERS;
				break;
			default :
		}
	}
	
	$viewType = checkType($app);
	return new loadRunMVCComponents ( "UserModel", "UserController", $viewType, $action, $app, $parameters );
} )->via ( "GET");


$app->map ( "/films(/:id)", function ($filmsID = null) use($app) {
	$httpMethod = $app->request->getMethod ();
	$action = null;
	$parameters ["id"] = $filmsID; // prepare parameters to be passed to the controller (example: ID)
	if (($filmsID == null) or is_numeric ( $filmsID )) {
		switch ($httpMethod) {
			case "GET" :
				if ($filmsID != null)
					$action = ACTION_GET_FILM;
				else
					$action = ACTION_GET_FILMS;
				break;
			case "POST" :
				$action = ACTION_CREATE_FILM;
				break;
			case "PUT" :
				$action = ACTION_UPDATE_FILM;
				break;
			case "DELETE" :
				$action = ACTION_DELETE_FILM;
				break;
			default :
		}
	}
	
	$viewType = checkType($app);
	return new loadRunMVCComponents ( "FilmModel", "FilmController", $viewType, $action, $app, $parameters );
} )->via ( "GET", "POST", "PUT", "DELETE" );


$app->map ( "/films/search(/:string)", function ($searchString = null) use($app) {
	$httpMethod = $app->request->getMethod ();
	$action = null;
	$parameters ["searchString"] = $searchString; // prepare parameters to be passed to the controller (example: ID)
	if (($searchString == null) or is_string( $searchString )) {
		switch ($httpMethod) {
			case "GET" :
				$action = ACTION_SEARCH_FILMS;
				break;
			default :
		}
	}
	
	$viewType = checkType($app);
	return new loadRunMVCComponents ( "FilmModel", "FilmController", $viewType, $action, $app, $parameters );
} )->via ( "GET");



$app->map ( "/film_details(/:id)", function ($filmsDetailsID = null) use($app) 
{
	$httpMethod = $app->request->getMethod ();
	$action = null;
	$parameters ["id"] = $filmsDetailsID; // prepare parameters to be passed to the controller (example: ID)
	if (($filmsDetailsID == null) or is_numeric ( $filmsDetailsID ))
	{
		switch ($httpMethod)
		{
			case "GET" :
				if ($filmsDetailsID != null)
					$action = ACTION_GET_FILM_DETAILS;
				else
					$action = ACTION_GET_FILMS_DETAILS;
				break;
			case "POST" :
				$action = ACTION_CREATE_FILM_DETAILS;
				break;
			case "PUT" :
				$action = ACTION_UPDATE_FILM_DETAILS;
				break;
			case "DELETE" :
				$action = ACTION_DELETE_FILM_DETAILS;
				break;
			default :
		}
	}
	
	$viewType = checkType($app);
	return new loadRunMVCComponents ( "FilmDetailsModel", "FilmDetailsController", $viewType, $action, $app, $parameters );
} )->via ( "GET", "POST", "PUT", "DELETE" );


$app->map ( "/film_details/search(/:string)", function ($searchString = null) use($app) {
	$httpMethod = $app->request->getMethod ();
	$action = null;
	$parameters ["searchString"] = $searchString; // prepare parameters to be passed to the controller (example: ID)
	if (($searchString == null) or is_string( $searchString )) {
		switch ($httpMethod) {
			case "GET" :
				$action = ACTION_SEARCH_FILM_DETAILS;
				break;
			default :
		}
	}
	
	$viewType = checkType($app);
	return new loadRunMVCComponents ( "FilmDetailsModel", "FilmDetailsController", $viewType, $action, $app, $parameters );
} )->via ( "GET");



$app->run ();
class loadRunMVCComponents {
	public $model, $controller, $view;
	public function __construct($modelName, $controllerName, $viewName, $action, $app, $parameters = null) 
	{
		include_once "models/" . $modelName . ".php";
		include_once "controllers/" . $controllerName . ".php";
		include_once "views/" . $viewName . ".php";
		
		$model = new $modelName (); // common model
		$controller = new $controllerName ( $model, $action, $app, $parameters );
		$view = new $viewName ( $controller, $model, $app, $app->headers ); // common view
		$view->output (); // this returns the response to the requesting client
	}
}

?>