<?PHP

/**
 * @param $data $action
 */

class Login extends User {

	public function __construct($action) {
		parent::__construct($action);
		if ($this->userRunning()) {
			Router::redirect('../photobooth');
		}
		$this->loadModel('Account');
	}

	public function index($input = null) {
		if (!$this->userSet($input, ['pseudo/mail', 'password'])) {
			return false;
		} else {
			$username = $input['pseudo/mail'];
			$pass = $input['password'];
		}
		$user = $this->Account->loginDb($username, $pass);
		if($user && password_verify($pass, $user->account_pass)) {
			if (isset($input['remember'])) {
				$this->Account->remember($user->account_id);
			}
			$this->connect($user);
			$this->session->setFlash('success', "Well come back");
			Router::redirect('../photobooth');
			exit();
		}
		$this->session->setFlash('danger', "Id or password incorrect! :(");
	}

	public function resetPass($input) {
		if ($this->userSet($input, ['mail'])) {
			if ($this->Account->resetSendMail($input['mail'])){
				$this->session->setFlash('success', "Instructions for recovering your password will be sent to you.");
				Router::redirect('../login');
				exit();
			} else {
				$this->session->setFlash('danger', "There is no account corresponding to this email.");
			}
		}
	}

	public function recover($input) {
		$ret = Router::getELemUri($_SERVER['REQUEST_URI']);
		$user = $this->Account->checkResetToken($ret['id'], $ret['token']);
		if ($user) {
			if (!empty($input)) {
				if ($input['new_password'] === $input['confirm_new_password']) {
					$this->isPass('new_password', $input['confirm_new_password']);
					if ($this->isValid()) {
						$password = password_hash($input['new_password'], PASSWORD_BCRYPT);
						$this->Account->resetPass($password, $ret['id']);
						$this->session->setFlash('success', "Password modified with success !");
						$this->connect($user);
						Router::redirect('../account');
					} else {
						$this->getErr();
					}

				} else {
					$this->setErr('pass', 'Typing Pass are not the same');
					$this->getErr();
				}
			}
		} else {
			$this->session->setFlash('danger', "This token has expired.");
			Router::redirect('../login');
			exit();
		}
	}

}
