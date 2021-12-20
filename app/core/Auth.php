<?php
    class Auth
    {
        private $session;

        public function __construct($session)
        {
            $this->session = $session;
        }

        public function get_auth()
        {
            return $this->session->read('auth');
        }

        public function connect($user)
        {
            $this->session->write('auth', $user);
        }

        public function logout()
        {
            $this->session->delete('auth');
        }
    }
?>