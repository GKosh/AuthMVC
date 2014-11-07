<?php
if(!defined("BASE_URL")) die;  
/*

авторизации и просмотра списка пользователей.
Методы User класса
index - метод который должен вызваться по умолчанию
loadModel - Загружает модель по имени в свойство с соответсвующим именем
loadView  - Загружает соответсвующий вид (view) и применяет к нему массив 
переданных параметров используя php функцию extract, возвращает строку сформированного html
registration - отображает страницу регистрации (/views/registration.php), и регистрирует пользователя если передаются соответсвующие параметры, производится проверка входящих параметров, ошибка возвращается на страницу.
authorization - отображает страницу авторизации (/views/authorization.php), и регистрирует авторизует пользователя если передаются соответсвующие параметры, производится проверка входящих параметров, ошибка возвращается на страницу.
users - отображает страницу списка пользователей (/views/users.php), удаляет, редактирует, или создает пользователя если передаются соответствующие параметры


*/

class User extends AppController
{
	
	private $default_layout = 'layout';
	private $default_view = 'authorisation';
	public $response = array();
	
	function __construct(){
		$this->loadModel('user');
		$this->set(array('CSS'=>BASE_URL . '/data/css/view.css'));
		$this->set(array('JS'=>BASE_URL . '/data/js/view.js'));
	}

	public function index(){
		if (!$this->loginCheck()){
			$this->authorisation();
		} else { 
			$this->users(); 
		}
	}

	private function loadModel($model_name){
		if(empty($model_name)){
			$model_name = get_class($this);  
		}  
		require_once MDL_PATH . $model_name . "_model.php";
			$model_class = $model_name . "Model"; 
			$this->$model_name = new $model_class;
			 
	}

	private function loadView($view_name){
	if(empty($view_name)){
		$view_name = $this->default_view;  
	} else { $view_name = strtolower($view_name);
	}
	if (!$this->request['ajax']){
	$this->set(array('header_path'=> VIEW_PATH . "header.php"));
	$this->set(array('content_path'=> VIEW_PATH . $view_name . ".php"));
	$this->set(array('footer_path'=>VIEW_PATH . "footer.php"));
	
	extract($this->data,EXTR_OVERWRITE);
	if ((!empty($this->default_layout)) && (file_exists(VIEW_PATH . $this->default_layout . ".php"))) {
			include(VIEW_PATH . $this->default_layout . ".php");
	} else { 
		echo "Something bad happened(layout not found) =(" ;
	}
	} else{
		extract($this->data,EXTR_OVERWRITE);
		if (file_exists(VIEW_PATH . $this->default_layout . ".php")) {
			include(VIEW_PATH . $view_name . ".php");
		}
	}
}




public function registration(){
		if ($this->loginCheck()){
			header('Location: '.BASE_URL);
			return false;
		} 
		
	$this->set(array(
		'regJS'=>BASE_URL . '/data/js/registration.js',
		'title'=>'Регистрация'
		));
	
	if ($this->request['ajax']){
		$this->response['success'] = false;
		if (isset($this->user)){
			if (!empty($this->request['email'])&&(!empty($this->request['password']))){
			if (!filter_var($this->request['email'], FILTER_VALIDATE_EMAIL)) {
				$this->response['error'] = "Invalid email format"; 
			} else {
				$uppercase = preg_match('@[A-Z]@', $this->request['password']);
				$lowercase = preg_match('@[a-z]@', $this->request['password']);
				$number    = preg_match('@[0-9]@', $this->request['password']);
				if(!$uppercase || !$lowercase || !$number || strlen($this->request['password']) < 6) {
				$this->response['error'] =" Password is not safe.Please provide password 6 to 15 characters with numbers, letters and/or underscore.";
			} else {
				$userData = array();
				$userData['email'] = $this->request['email'];
				$userData['password'] = md5($this->request['password']);
				if ($this->user->addUser($userData)) {
				$this->response['success'] = true;
				$this->response['success_redirect'] = BASE_URL . 'user/users';
				}
			}
			}
			}else $this->response['error'] = "Something bad happened: email and password has not been received =(";
		}else $this->response['error'] = "Something bad happened: userModule is not defined =("; 

		echo json_encode($this->response);	
	} else{

	
	$this->loadView('registration');
	}
}

public function authorisation(){
	if ($this->loginCheck()){
			header('Location: '.BASE_URL . "/user/users");
			return false;
	} 
	$this->set(array(
		'authJS'=>BASE_URL . '/data/js/authorisation.js',
		'title'=>'Вход'
		));
	
	if ($this->request['ajax']){
		$this->response['success'] = false;
		$this->response['error'] = '';
		if (isset($this->user)){
			if (!empty($this->request['email'])&&(!empty($this->request['password']))){
				$userData['email'] = $this->request['email'];
				$userData['password'] = md5($this->request['password']);
				$result = $this->user->getUser($userData);
				if ((!empty($result))&&($result[0]['Active'])){
				$this->response['success'] = true;
				$this->login($result[0]['ID']);
				$this->response['success_redirect'] = BASE_URL . 'user/users';
				} else $this->response['error'] = "Something bad happened: user not found! Maybe email or password is wrong or your account deactivated =( Try once again or contact administrator";
			} else $this->response['error'] = "Something bad happened: email and password has not been received =(";
		}else $this->response['error'] = "Something bad happened: userModule is not defined =("; 
	
		echo json_encode($this->response);	
	} else{
		$this->loadView('authorisation');
	}
}

public function users(){
		if (!$this->loginCheck()){
			header('Location: '.BASE_URL);
			return false;
		}
	$this->set(array(
		'usersJS'=>BASE_URL . '/data/js/users.js',
		'title'=>'Список пользователей'
		));
	if ($this->request['ajax']){
		$this->response['success'] = false;
		if (isset($this->user)){
			$userData = array();
			switch($this->request['params'][0]){
				case "update":
					$userData['id'] = $this->request['id'];
					$userData['email'] = $this->request['email'];
					$userData['password'] = $this->request['password'];
					if ($this->user->editUser($userData)) $this->response['success'] = true;
					break;
				case "delete":
					$userData['id'] = $this->request['id'];
					if ($this->user->deleteUser($userData)) $this->response['success'] = true;
					break;
				case "add":
					$userData['email'] = $this->request['email'];
					$userData['password'] = $this->request['password'];
					if ($this->user->addUser($userData)) $this->response['success'] = true;
					break;	
				case "logout":	
					$this->response['success'] = $this->logout();
					$this->response['success_redirect'] = BASE_URL ;
					echo json_encode($this->response);
					
					break;					
			}
	
		}else $this->response['error'] = "Something bad happened: userModule is not defined =("; 
		if (($this->request['params'][0]) != 'logout'){
		$users = $this->user->usersList();
		$this->set(array('users'=>$users));
		$this->loadView('users_table');	
		}
	} else{
	$users = $this->user->usersList();
	$this->set(array('users'=>$users));
	$this->loadView('users');
	}
}

public function loginCheck(){
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
		if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900)) {
			$this->logOut();
			return false;
		}
		$_SESSION['LAST_ACTIVITY'] = time(); 
		if (!isset($_SESSION['CREATED'])) {
			$_SESSION['CREATED'] = time();
		} else if (time() - $_SESSION['CREATED'] > 900) {
        session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
		$_SESSION['CREATED'] = time();  // update creation time
		}
		return true;
	} else return false;
}


public function login($id){
	$_SESSION['loggedin'] = true;
	if (!empty($id)) $_SESSION['userid'] = $id; 
}

public function logout(){
	unset($_SESSION["userid"]);
	unset($_SESSION["loggedin"]);
	session_unset();   
	session_destroy();
	return true;
}

}