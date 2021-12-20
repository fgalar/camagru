<?php

class Member extends User {

	function __construct($data) {
		parent::__construct($data);
		if (!$this->userRunning()) {
			$this->session->setFlash('danger', "You must have an account for access this page.");
			Router::redirect('../user/login');
			exit(-1);
		}
		$this->loadModel('Account');
		$this->loadModel('Photo');
	}

	public function index() {

	}

	function majInfo() {
		$maj = -1 ;

		if (strcmp($_SESSION['auth']->account_name, $_POST['name']) && ($maj = 1)) {
			$this->isName('name', "Please enter a name without special caractere, and space.");
			$this->Account->isUniq('name', $_POST['name']) ? : $this->setErr('name', "This username is already take.");
			if ($this->isValid()) {
				$this->Account->resetName($_POST['name'], $_SESSION['auth']->account_id);
				$_SESSION['auth']->account_name = $_POST['name'];
			}

		}

		if (strcmp($_SESSION['auth']->account_mail, $_POST['mail']) && ($maj = 1)) {
			$this->isEmail('mail', "You're email is not valid.");
			$this->Account->isUniq('mail', $_POST['mail']) ? : $this->setErr('mail', "It seems like this mail account already exists.");
			if ($this->isValid()) {
				$this->Account->resetMail($_POST['mail'], $_SESSION['auth']->account_id);
				$_SESSION['auth']->account_mail = $_POST['mail'];
			}
		}

		if (!empty($_POST['pass']) && ($maj = 1)) {
			$pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);
			if (!preg_match("/(?=.*[\d])[\w]{8,15}/", $_POST['pass'])) {
				$this->setErr('password', "Your password must contain at least 8 characters with letters and number.");
			}
			if ($this->isValid()) {
				$this->Account->resetPass($pass, $_SESSION['auth']->account_id);
				$_SESSION['auth']->account_pass = $pass;
			}
		}

		if ($_SESSION['auth']->account_acceptMail == '0' && !empty($_POST['acceptMail']) && ($maj = 1)) {
			$this->Account->resetAcceptMail(1, $_SESSION['auth']->account_id);
			$_SESSION['auth']->account_acceptMail = 1;
		} else if ($_SESSION['auth']->account_acceptMail == '1' && empty($_POST['acceptMail']) && ($maj = 1)) {
			$this->Account->resetAcceptMail(0, $_SESSION['auth']->account_id);
			$_SESSION['auth']->account_acceptMail = 0;
		}

		if ($this->isValid() && $maj > 0) {
			$this->session->setFlash('success', "maj effectuée");
			Router::redirect('../account');
		} else if ($maj > 0) {
			$this->session->setFlash('danger', 'Opsi, smthg went wrong');
			$this->getErr();
		}

	}

	function removeImg($id) {

		$photo = $this->Photo->getImgbyId($id['postId']);

		unlink($photo->photo_path);

		$this->Photo->removePhoto($id['postId']);
	}

}