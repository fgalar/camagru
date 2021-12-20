<?PHP

class Register extends User {

	public function __construct($action) {
		parent::__construct($action);
		if ($this->userRunning()) {
			Router::redirect('../../photobooth');
		}
		$this->loadModel('Account');
	}

	public function index($input) {
		if (!$this->userSet($input, ['pseudo', 'mail', 'password'])) {
			return false;
		} else {
			$name = $input['pseudo'];
			$mail = $input['mail'];
			$pass = $input['password'];
		}
		if ($this->checkInput($input)){
			$this->Account->addAccount($name, $mail, $pass);
			$this->session->setFlash('success', "Please check your mailbox. We send you a message.");
			Router::redirect('login');
			exit();
		} else {
			$this->session->setFlash('danger', "You hasn't well answered to our form.");
			$this->getErr();
		}
	}

	public function confirmed() {
		$ret = Router::getELemUri($_SERVER['REQUEST_URI']);

		if ($user = $this->Account->confirm($ret['id'], $ret['token'])) {
			$this->session->setFlash('success', "Your account is validated");
			$this->connect($user);
		} else {
			$this->session->setFlash('danger', 'This token has expired.');
			Router::redirect('login');
			exit();
		}
	}

	public function checkInput($input) {

		$this->isName('pseudo', "Please enter a name without special caractere, and space.");
		$this->Account->isUniq('name', $input['pseudo']) ? : $this->setErr('name', "Your pseudo is already take.");

		$this->isEmail('mail', "You're email is not valid.");
		$this->Account->isUniq('mail', $input['mail']) ? : $this->setErr('mail', "It's seems like you have already a account here.");

		$this->isPass('password', $input['password']);
		return $this->isValid();
	}

}