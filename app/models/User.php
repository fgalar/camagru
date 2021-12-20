<?php

	class User extends Model {

		public function __construct(){
			parent::__construct();
		}

		public function new_user($params)
		{
			// $params : username(str) | mail(str) | password(str)
			$new_user = $this->front_to_back_trad($params);
			$new_user['confirmkey'] = random(60);
			$user = $this->insert($new_user);
			return $user;
		}

		public function is_unique($params)
		{
			$user = $this->front_to_back_trad($params);
			$user_id = $this->select_one($user);
			return empty($user_id);
		}

		public function confirm_key($params)
		{
			$confirm = $this->front_to_back_trad($params);
			$user = $this->select_one($confirm);
			if ($user)
			{
				return $this->update(
					['confirmation' => True],
					['id' => $user->id]);
			} else
				return 0;
		}

		public function exist($user)
		{
			$pwd = $user['password'];

			$user = $this->front_to_back_trad($user);
			unset($user['pwd']);
			$user = $this->select_one($user, '*');

			if ($user && password_verify($pwd, $user->pwd))
				return $user;
			return 0;
		}

		public function mail_or_login_exist($user)
		{
			$db = $this->get_pdo();
			$sql = "SELECT * FROM `users` WHERE `mail` = :identifier OR `login`= :identifier";

			$stmt = $db->prepare($sql);
			$stmt->execute(['identifier' => $user['login/mail']]);
			return $stmt->fetch();
		}

		public function reset_token($user)
		{
			$updated_user = $this->update(
				['confirmkey' => random(60)], $user);
			return $updated_user->confirmkey;
		}

		public function reset_pwd($pwd, $user)
		{
			# $pwd : new pwd to set
			# $user : [$field=>$value] to be settled.

			$pwd = $this->front_to_back_trad($pwd);
			$user =$this->front_to_back_trad($user);

			return $this->update($pwd, $user);
		}

		public function update_user_information($user_id, $params)
		{
			# $user_id : (int)user to update.
			# $params : $params to be updated.

			$params = $this->front_to_back_trad($params);
			return $this->update($params, ['id' => $user_id]);
		}

		public function front_to_back_trad($params)
		{
			$dico = [];
			foreach ($params as $key=>$value)
			{
				if ($key == 'url') {
					unset($params[$key]);
				} elseif ($key == 'username') {
					$dico['login'] = $value;
				} elseif ($key == 'password') {
					$dico['pwd'] = password_hash($value, PASSWORD_BCRYPT);
				} elseif ($key == 'token') {
					$dico['confirmkey'] = $value;
				} else {
					$dico[$key] = $value;
				}
			}
			return $dico;
		}

	}
