<?php

    class Dispatcher {

        var $request;

        function __construct() {
            $this->request = Router::parse();
            $controller = $this->loadController($this->request);
            if (!in_array($this->request['action'], get_class_methods($controller))) {
                $controller->not_found();
                exit();
            }
            call_user_func_array(array($controller, $this->request['action']), $this->request['params']);
        }

        function loadController($route) {
            $controller = ucfirst($route['controller']);
            $file = 'controllers/' . $controller . '.php';
            if (file_exists($file)) {
                require_once $file;
                return new $controller($route);
            }
            return new Controller($route);
        }
    }