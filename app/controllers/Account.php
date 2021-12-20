<?php

    class Account extends Controller
    {
        private $_form;

        public function __construct()
        {
            parent::__construct();
            $this->_form = new Form();
        }
    # SIGN UP : (Form -> send mail -> insert in DB)
        public function signup()
        {
            $d = ['nav_title'   => 'Sign up',
				  'title' 	    => 'Sign up',
                  'action'      => 'account/create',
				  'form'	    => [
					$this->_form->input('username', 'Login : ', 'text'),
					$this->_form->input('mail', 'Email : ', 'email'),
					$this->_form->input('password', 'Password : ', 'password'),
					$this->_form->submit('Sign Up')]];
            $this->set($d);
            $this->render('form');
        }

        public function create()
        {
            $new_user = $_POST;
            if ($this->_form->check_input($new_user))
            {
                $user = $this->users->new_user($new_user);
                $this->auth->connect($user);
                Router::redirect('/mail/confirmation/');
            } else {
                $this->session->setFlash('danger', 'Please review your form answers.');
                Router::redirect('/account/signup');
                $this->_form->getErr();
            }

        }

        public function confirmed()
        {
            if (isset($_GET['id'], $_GET['token']))
            {
                if ($user = $this->users->confirm_key($_GET))
                {
                    $this->auth->connect($user);
                    $this->session->setFlash(
                        'success',
                        "Your account is validated");
                    Router::redirect('/gallery');
                } elseif (!$user) {
                    $this->session->setFlash(
                        'danger',
                        'Bad url: user id or token not valid.');
                    Router::redirect('/signup');
                    exit();
                }
            }
        }

    # LOG IN
        public function signin()
        {
            $d = ['nav_title'	=> 'Sign in',
				  'title' 	=> 'Sign in',
                  'action' => 'account/login',
                  'form'	=> [
					$this->_form->input('username', 'Login : ', 'text'),
					$this->_form->input('password', 'Password : ', 'password'),
					$this->_form->submit('Login')]];
            $this->set($d);
            $this->render('form');
        }

        public function login()
        {
            $input = $_POST;
            if ($user = $this->users->exist($input))
            {
                if ($user->confirmation)
                {
                    $this->auth->connect($user);
                    $this->session->setFlash(
                        'success',
                        "Well come back $user->login ! 👋");
                    Router::redirect('/gallery');
                    exit();
                } else {
                    $this->session->setFlash(
                        'danger',
                        "Please confirm your mail before continued. ⇢ 💌"
                    );
                    Router::redirect('/gallery');
                    exit();
                }
            }
            $this->session->setFlash('danger', 'Oups, Identifiers not correct. 🤭');
            Router::redirect('/account/signin');
            exit();
        }

    # LOGOUT
        public function signout()
        {
            $this->auth->logout();
            Router::redirect('/gallery');
        }

    # RESET PASSWORD : (Form -> mail -> Form -> New Password -> insert in DB)
        public function reset_form_send_mail()
        {
            $d = ['nav_title'   => 'Reset Password',
                  'title' 	    => 'Connexion Problem? ',
                  'action'      => 'account/reset_password_send_mail',
                  'form'	    => [
                    $this->_form->input('login/mail', 'Login or Email : ', 'text'),
                    $this->_form->submit('Reset Password')]];
            $this->set($d);
            $this->render('form');
        }

        public function reset_password_send_mail()
        {
            $input = $_POST;

            if (isset($_POST['login/mail']))
            {
                if ($user = $this->users->mail_or_login_exist($input))
                {
                    $new_token = $this->users->reset_token(['id' => $user->id]);
                    Router::redirect(
                        "/mail/reset_password?id=".$user->id ."&mail=" . $user->mail . "&token=" . $new_token);
                } else {
                    $this->session->setFlash(
                        'danger',
                        "There is no account corresponding to this email or login.");
                    Router::redirect('/account/reset_form_send_mail');
                }
            }
        }

        public function new_pwd_form()
        {
            if ($user = $this->users->confirm_key($_GET))
            {
                $d = ['nav_title'   => 'Reset Password',
                      'title' 	    => 'Connexion Problem? ',
                      'action'      => "account/update_pwd?id=$user->id&token=$user->confirmkey",
                      'form'	    => [
                        $this->_form->input('new_pwd', 'New Password : ', 'password'),
                        $this->_form->input('confirm_pwd', 'Confirm New Password : ', 'password'),
                        $this->_form->submit('Reset Password')]];
                $this->set($d);
                $this->render('form');
            }
            else
            {
                $this->session->setFlash('danger', "Invalid Token.");
                Router::redirect('/gallery');
            }
        }

        public function update_pwd()
        {
            $input = $_POST;
            $user = $_GET;

            if ($input['new_pwd'] == $input['confirm_pwd'])
            {
                $this->_form->isPass('new_pwd', $input['new_pwd']);
                if ($this->_form->isValid())
                {
                    $user = $this->users->reset_pwd(['password' => $input['new_pwd']], $user);
                    $this->auth->connect($user);
                    $this->session->setFlash(
                        'success',
                        "Password updated with success. 🎉");

                    Router::redirect('/gallery');
                }
                else
                {
                    $this->_form->getErr();
                    Router::redirect(
                        '/account/new_pwd_form?id=' . $user['id'] . '&token=' . $user['token']);
                }
            }
            else
            {
                $this->session->setFlash(
                    'danger', "The passwords entered differ. 👀");
                Router::redirect('/account/new_pwd_form?id=' . $user['id'] . '&token=' . $user['token']);
            }
        }
    }