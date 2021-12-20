<?php
/**
 * Controller User dispatch throught differents states of Userlog: register, login, logout
 * @param $_request tab [controler, action, params]
 * @param $_userSet POST
 **/
require "core/Form.php";
class User extends Controller {

	protected	$_request;
	private		$_errors = [];

	public function __construct($data) {
		parent::__construct($data);
		$this->_request = $data;
	}
	/**
	* @param input $_POST
	* @param to_set param of $_POST to be set
	* @return bool : true if all $to_set are set | false
	**/
	public function userSet($input, $to_set) {
		if (empty($input)) { return false; }
		foreach($to_set as $set) {
			if (!isset($input[$set])) {
				return false;
			}
		}
		return true;
	}

	public function connect($user) {
		$this->session->write('auth', $user);
	}

	public function login($param = 'index') {
		$data = 'load'. ucfirst($param) . 'Data';
		$this->$data();
		$ctrl = new Login($param);
		call_user_func_array(array($ctrl, $param), [$_POST]);
	}

	public function logout() {
		$this->loadModel('Account');
		setcookie('remember', NULL, -1, rtrim(Router::getWebRoute(), '/'));
		$this->session->delete('auth');
		$this->session->setFlash('success', "You're disconnected. See You! 😘");
		Router::redirect('login');
		exit();
	}

	public function register($param = 'index') {
		$this->loadRegisterData();
		$ctrl = new Register($param);
		call_user_func_array(array($ctrl, $param), [$_POST]);
	}

	public function account($param = 'index') {
		$this->loadMemberData();
		$ctrl = new Member($param);
		call_user_func_array(array($ctrl, $param), [$_POST]);
		$this->render('member');
	}

	/**  DataForm  **/

	public function loadIndexData() {
		$form = new Form();

		$d = [  'nav_title'		=> 'Connexion',
				'title' 		=> 'Login',
				'form'	=> [
					$form->input('pseudo/mail'),
					$form->input('password', 'password'),
					$form->input('remember', 'checkbox'),
					$form->submit('Sign In')]];
		$this->set($d);
	}

	public function loadResetPassData() {
		$form = new Form();

		$d = [	'nav_title' 	=> "Reset Password",
				'title'			=> "Oh no! You've forgotten your password...",
				'form'	=> [
					$form->input('mail', 'email'),
					$form->submit('Send me a mail')]];

		$this->set($d);
	}

	public function loadRecoverData() {

		$form = new Form();

		$d = [	'nav_title' 	=> 'Reset Password',
				'title'			=> 'Set your new Password',
				'form'			=> [
					$form->input('new password', 'password'),
					$form->input('confirm new password','password'),
					$form->submit('Reset my password')]];

		$this->set($d);
	}

	public function loadRegisterData() {
		$form = new Form();

		$d = [  'nav_title'	=> 'Sign up',
				'title' 	=> 'Sign up',
				'form'	=> [
					$form->input('pseudo'),
					$form->input('mail', 'email'),
					$form->input('password', 'password'),
					$form->submit('Sign Up')]];
		$this->set($d);
	}

	public function loadMemberData() {
		$this->loadModel('Photo');

		$form = new Form($_SESSION['auth']);

		$d = [	'nav_title'		=> "Profil",
				'form'	=> [
					$form->input('name'),
					$form->input('mail', 'email'),
					$form->input('pass', 'password'),
					$form->input('acceptMail', 'checkbox', 'Send a mail when somebody comment one of my post'),
					$form->submit('Confirm changes!')]];

		$this->set($d);
		$posts = $this->Photo->getPostUser($_SESSION['auth']->account_id);
		$this->set('selfies', $posts);
	}

	/**  Validation of datas entered by user  **/

		public function isValid() { return empty($this->_errors); }

		public function isName($field, $errMsg) {
			if (!preg_match('/^[a-zA-Z0-9_-]+$/', $this->getField($field))) {
				$this->_errors[$field] = $errMsg;
			}
		}

		public function isEmail($field, $errMsg) {
			if (!filter_var($this->getField($field), FILTER_VALIDATE_EMAIL)) {
				$this->_errors[$field] = $errMsg;
			}
		}

		public function isPass($field, $pass) {
			if (empty($this->getField($field)))	{
				$this->_errors[$field] = "Password field is empty.";
			}
			if (!preg_match("/(?=.*[\d])[\w]{8,15}/", $pass)) {
				$this->_errors[$field] = "Password must contain a number and at least 8 characters.";
			}
		}

		/**
		 * @param field of error: 'name' 'mail' 'mdp'
		 * @return field
		 **/
		private function getField($field) {
			if (!isset($_POST[$field])) {
				return null;
			}
			return $_POST[$field];
		}

		public function setErr($field, $msg) {
			$this->_errors[$field] = $msg;
		}

		public function getErr() {
			if (!empty($this->_errors)) {
				$d = implode('<br/>', $this->_errors);
				$this->session->setFlash('danger', $d);
			}
		}

}
