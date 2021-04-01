<?php

/**
 * The Dispatcher get the Router object: Array (
 *	[controller]	=> gallery
 *	[action] 		=> view
 *	[params] 		=> Array ( [0] => example ) )
 * and then redirect it through the good controller.
 **/
require_once './core/functions.php';

class Dispatcher {

	var $request;

	function __construct() {
		$this->request = Router::parse();
		$controller = $this->loadController($this->request);

		if (!in_array($this->request['action'], get_class_methods($controller))) {
			$controller->e404('Le controller ' . $this->request['controller'] . " haven't got method: " . $this->request['action']);
		}
		call_user_func_array(array($controller, $this->request['action']), $this->request['params']);
		$controller->render($this->request['controller']);
	}

	/**
	* Create the good Controller
	* @param $params array([controller], [action], [params][])
	* @return $controller if exist OR new Controller where error will be managed
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
