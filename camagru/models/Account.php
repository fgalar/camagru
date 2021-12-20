<?php
/**
 * Requete spécifique à la gestion user:
 * [] Register
 * [] Login
 *
 **/
class Account extends Model {

	public function __construct(){
		parent::__construct();
	}

	public function remember($id) {
		$token = Str::random(250);
		$this->update(array(
			'to_update' 	=> 'account_tokenRememberMe = ?',
			'conditions' 	=> 'account_id = ?',
			'params'	=> [$token, $id]
		));
		setcookie('remember', $id . '==' . $token . sha1($id), time() + 60 * 60 * 24 * 7, rtrim(Router::getWebRoute(), '/'));
	}

	public function isUniq($field, $to_find)	{
		$sql = 'SELECT account_id FROM Camagru. ' . $this->table . ' WHERE account_' . $field . '= ?';
		return empty($this->query($sql, [$to_find])->fetch());
	}

	public function addAccount($name, $mail, $password) {
		$pass = password_hash($password, PASSWORD_BCRYPT);
		$token = random(60);
		$this->set([
			'to_set' =>	[	'login = ?',
							'mail = ?',
							'pwd = ?',
							'confirmkey = ?'],
			'params' => [$name, $mail, $pass, $token]]);
		$account_id = $this->lastInsertId();
		$path = Router::getWebRoute();
		mail($mail,
			'Confirmation of your account',
			"In order to confirm your account, please click on this link:\n\n
			http://localhost:8888".$path."user/register/confirmed?id=$account_id&token=$token",
			array(
				'From' => 'fgarault@camagru.42.fr'));
	}

	public function getAccountById($id) {
		$user = $this->find(['conditions' => 'account_id = ?', 'params' => [$id]]);
		return $user->fetch();
	}

	public function confirm($user_id, $token)	{

		$user = $this->find([
			'conditions'	=> 'account_id = ?',
			'params'		=> [$user_id]])->fetch();
		if ($user && $user->account_token == $token) {
			$this->update([
				'to_update'	=> ['account_token = NULL',
								'account_confirmedAt = NOW()'],
				'conditions'=> 'account_id = ?',
				'params'	=> [$user_id]]);
			return $user;
		}
		return false;
	}

	public function loginDb($username) {
		$user = $this->find([
			'conditions'	=>	['(account_name = :username OR account_mail = :username)',
								 'account_confirmedAt IS NOT NULL'],
			'params' 		=>	[':username' => $username]])->fetch();
		return $user;
	}

	public function resetSendMail($mail) {
		$user = $this->find(['conditions' => [	'account_mail = ?',
												'account_confirmedAt IS NOT NULL'],
							'params' 	=> [$mail]])->fetch();
		if ($user)	{
			$reset_token = Str::random(60);
			$this->update([
			'to_update' 	=> ['account_tokenResetPass = ?',
								'account_resetPassAt = NOW()'],
			'conditions'	=>	'account_id = ?',
			'params' 		=>	[$reset_token, $user->account_id]]);
			$path = Router::getWebRoute();
			mail($mail,
				'Recover your password',
				"Click on this link for reinitialize your password :\n\n
				http://localhost:8888".$path."user/login/recover?id={$user->account_id}&token=$reset_token",
				array(
					'From' => 'fgarault@camagru.42.fr'));
			return $user;
		}
		return false;
	}

	public function checkResetToken($id, $token) {
		return $this->find([
			'conditions'	=>	['account_id = ?',
								'account_tokenResetPass = ?',
								'account_resetPassAt < DATE_SUB(NOW(), INTERVAL 30 MINUTE)'],
			'params'		=>	[$id, $token]])->fetch();
	}

	public function resetPass($pass, $id) {
		$this->update([
			'to_update'		=>	['account_pass = ?',
								 'account_tokenResetPass = NULL',
								 'account_resetPassAt = NULL'],
			'conditions'	=>	 'account_id = ?',
			'params'		=>	[$pass, $id]]);
	}

	public function resetName($name, $id) {
			$this->update([
				'to_update'		=>	'account_name = ?',
				'conditions'	=>	 'account_id = ?',
				'params'		=>	[$name, $id]]);

	}

	public function resetMail($mail, $id) {

		$this->update([
			'to_update'		=>	'account_mail = ?',
			'conditions'	=>	 'account_id = ?',
			'params'		=>	[$mail, $id]]);

	}

	public function resetAcceptMail($wish, $id) {

		$this->update([
			'to_update'		=>	'account_acceptMail = ?',
			'conditions'	=>	'account_id = ?',
			'params'		=>	[$wish, $id]]);

	}

}
