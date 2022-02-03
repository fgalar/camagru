<?php
	class Mail extends Controller {

		function __construct() {
			parent::__construct();
		}

		function confirmation()
		{
			$user = $_SESSION['auth'];
            $to = $user->mail;
            $subject = "confirm your email.";
            $message = "In order to confirm your account, please click on this link:\r\n\n";

			$link = $_SERVER['REQUEST_SCHEME'] . '://';	// http://
			$link .= $_SERVER['SERVER_NAME'];			// 127.0.0.1
			$link .= "/account/confirmed?id=" . $user->id . "&token=" . $user->confirmkey;

			if (mail($to, $subject, $message . $link))
			{
				$this->session->setFlash(
                    'success', 'An email has just been sent to you.');
				$this->auth->logout();
                Router::redirect('/gallery');
			} else {
				$this->session->setFlash('danger', 'Ups, mail not sent for some reason.');
                Router::redirect('/account/signup');
			}
		}

		function reset_password()
		{
            $user = $_GET;
			$to = $_GET['mail'];
            $subject = "Reset Password has been request.";
            $message = "In order to reset your password, please click on this link:\r\n\n";

			$link = $_SERVER['REQUEST_SCHEME'] . '://';	// http://
			$link .= $_SERVER['SERVER_NAME'];			// 127.0.0.1
			$link .= "/account/new_pwd_form?id=" . $user['id'] . "&token=" . $user['token'];

			if (mail($to, $subject, $message . $link))
			{
				$this->session->setFlash(
                    'success', 'An email has just been sent to you.');
                Router::redirect('/gallery');
				exit();
			} else {
				$this->session->setFlash('danger', 'Ups, mail not sent for some reason.');
                Router::redirect('/gallery');
				exit();
			}
		}

		function new_comment_on_post($user, $comment, $by_user)
		{
			$to = $user->mail;
			$subject = "New comment on your post";
			$message ="Hey ". $user->login . " you've got a new comment : '$comment' \n \tby $by_user";

			mail($to, $subject, $message);
		}
	}
