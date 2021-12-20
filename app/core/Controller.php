<?php
    class Controller {
        private $vars   = array(); //variable to pass on View
        private $rendered = false; // true if view rendered
        protected $session;
        protected $users;

        function __construct() {
            $this->users = $this->loadModel('User');
            $this->pictures = $this->loadModel('Picture');
            $this->session = new Session();
            $this->auth = new Auth($this->session);
            if (empty(session_id())) {
                $this->session = new Session();
            }
        }

        function loadModel($model) {
            $file = 'models/' . $model . '.php';
            if (file_exists($file)){
                require_once $file;
                return new $model();
            }
        }

        function set($key, $value = null) {
            if (is_array($key)) {
                $this->vars += $key;
            } else {
                $this->vars[$key] = $value;
            }
        }

        function render($view) {
            if ($this->rendered)
                return ;
            extract($this->vars);
            ob_start();
            require('./views/' . $view . '.php');
            $flash = $this->session->getFlashes();
            $content = ob_get_clean();
            require("./views/template.php");
            $this->rendered = true;
        }

        public function userRunning() {
            if ($this->session->read('auth'))	{
                return $this->session->read('auth');
            }
            return false;
        }

        public function not_found($msg = "This page is unfortunately not available.")
        {
            $this->set([
                'nav_title' => 'Not found',
                'msg' => "404 : " . $msg]);
            $this->render('404');
        }

        public function not_authorized($msg = "You're not authorized to access this page.")
        {
            $this->set([
                'nav_title' => 'Not found',
                'msg' => "403 : " . $msg]);
            $this->render('404');
        }
    }
