<?php

$timer = microtime(true);

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

// Current URL function
function CurpageURL(){
	$pageURL = 'http';
	if ((isset($_SERVER["HTTPS"])) && (strtolower($_SERVER["HTTPS"]) == "on")) $pageURL .= "s"; 
		$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80")
		$pageURL.=$_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . '/';
	else 
	$pageURL .= $_SERVER["SERVER_NAME"] . '/';
	
	return $pageURL;
}
/****************************
 Define environment settings 
****************************/

// Base URL
	define("BASE_URL", CurPageURL());
// Basic directories
	define("BASE_PATH",dirname(realpath(__FILE__)). DIRECTORY_SEPARATOR);
	define("CNTR_PATH", BASE_PATH . "controllers" . DIRECTORY_SEPARATOR);
	define("MDL_PATH", BASE_PATH . "models" . DIRECTORY_SEPARATOR);
	define("VIEW_PATH", BASE_PATH . "views" . DIRECTORY_SEPARATOR);
	define("LOG_PATH", BASE_PATH . "log" . DIRECTORY_SEPARATOR);
	
	
	
	// DB config

	define("DB_HOST","localhost");
	define("DB_USER","testdb");
	define("DB_PASS","testdb");
	define("DB_NAME","test-db");
	
	
/****************************
 Main controller (index class)
****************************/


class AppController {
	
	const DEFAULT_CONTROLLER = "user";
	const DEFAULT_METHOD = "index";
	public $controller = self::DEFAULT_CONTROLLER;
	public $method = self::DEFAULT_METHOD;
	public $params = array();
	public $request = array('ajax'=>false);
	public $data = array();

	public function __construct(){
		session_start();
		// загрузка класса БД
		$this->dbmodulepath = MDL_PATH . "db.php";
		if (file_exists($this->dbmodulepath)) {
			require_once($this->dbmodulepath) ;
		}
		// обработка УРЛ и загрузка контролера
		$this->parseRequest();
		$this->loadController();
	}
	
	private function parseRequest(){
		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
			$this->request['ajax'] = true;
		else $this->request['ajax'] = false;
		if(isset($_POST)) foreach ($_POST as $key=>$value){
			 $this->request[$key] = $value;
		};
		if(isset($_GET)) foreach ($_GET as $key=>$value){
			 $this->request[$key] = $value;
		};
		
		if ($_SERVER['REQUEST_URI'] != '/') {
			$URI_path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH),"/");
			@list($controller,$method,$params) = $URI_parts = explode('/', $URI_path);
			
			if ((isset($controller))&&($controller!="")){
				
				$this->controller = strtolower($controller);
				if (isset($method)){
					$this->method = strtolower($method);
				}
				if (!empty($params)){
				    $params = explode("/",$params);
					$this->params = $params;
				} 
			} 
		}
	
	}


	public function getController(){
		return $this->controller;
	}
	
	public function getMethod(){
		return $this->method;
	}
	
	public function getParams(){
		if (!empty($this->params)){
			return $this->params;
		} else {
			return false;
		}
	}
// Загрузка контролера	
	public function loadController($controller = null,$method = null,$params = null){
	    if ((empty($controller))||(empty($method))){
			$method = $this->getMethod();
			$controller = $this->getController();
			$params = $this->getParams();
		}
		$controllerPath = CNTR_PATH . $controller . ".php"; 
		if (file_exists($controllerPath)){
			require_once($controllerPath);
		// Запуск метода и передача параметров
			if (method_exists($controller,$method)){
				if (!empty($params)) $this->request['params'] = $params;
				$this->controller = new $controller;
				$this->controller->request = $this->request;
				$this->controller->$method();
			}
		} else { 
			echo "Sorry! This page cannot be found(controller doesn't exist)!"; 
		}
	}

	public function set($data){
		if (is_array($data)){
			foreach ($data as $item=>$value) {
				$this->data[$item] = $value;
			}
		}
	}	
			
	public function loginCheck(){
	
		return false;
	}

	
// log function 
	public function logMessage($message){
		file_put_contents( LOG_PATH . "log.txt", $message. "\n",FILE_APPEND);
	}

}
	
/****************************
Startup
****************************/

$App = new AppController();
 

/*

config - публичное свойство, которое содержит настройки базы данных и контроллер по умолчанию 
getController - возвращает имя контроллера
getMethod - возвращает имя метода
getParams - возвращает масив параметров для метода
loadController - подключает соответсвующий контроллер
конструктор - вызывает соответствующий метод у соответствующего контроллера 
*/
?>
