<?php

/**
 * La classe Dispatcher récupère l'objet Rooter;
 * Array ( [controller] => gallery [action] => view [params] => Array ( [0] => example ) )
 * et oriente vers le bon controller.
 **/
require_once './core/functions.php';
class Dispatcher {

	var $request;

	function __construct() {
		$this->request = Router::parse();
		$controller = $this->loadController($this->request);
		//debug(get_class($controller));
		if (!in_array($this->request['action'], get_class_methods($controller))) {
			$controller->e404('Le controller ' . $this->request['controller'] . " n'a pas de methode " . $this->request['action']);
		}
		call_user_func_array(array($controller, $this->request['action']), $this->request['params']);
		$controller->render($this->request['controller']);
	}

	/**
	* Permet d'orienter vers le bon controller
	* @param $params array([controller], [action], [params][])
	* @return $controller si existe OU new Controller ou l'erreur sera géré
	**/
	function loadController($params) {
		$controller = ucfirst($params['controller']);
		$file = './controllers/' . $controller  . 'Controller'. '.php';
		if (file_exists($file)){
			require $file;
			return new $controller($params);
		}
		return new Controller($params);
	}

}