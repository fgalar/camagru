<?php

class Settings extends Controller {

    private     $_form;

    public function __construct($data)
    {
        parent::__construct();
        if (!$this->userRunning())
			$this->not_authorized();
        $this->_form = new Form();
    }

    public function default() {
        $maj_login = ['nav_title'   => 'Edit Profile',
                      'action'      => 'settings/update_user',
                      'form'	    => [
                          $this->_form->input('username', 'Login : ', 'text', $this->userRunning()->login),
                        //   $this->_form->input('username', 'username', null, $this->userRunning()->login),
                          $this->_form->input('mail', 'Email : ', 'email', $this->userRunning()->mail),
                          $this->_form->input('password', 'Password : ', 'password'),
                          $this->_form->checkbox('sendmail', 'Send me a mail on comment : ', $this->userRunning()->sendmail),
                          $this->_form->submit('Submit')
                        ]];
            $this->set($maj_login);
        $this->render('member');
    }


    public function update_user() {

        $this->session->delete('danger');

        $input = [];

        foreach($_POST as $field=>$post)
        {
            if (!empty($post))
            {
                $input[$field] = $post;
                if ($field !== 'sendmail')
                {
                    $method = "update_" . $field;
                    $this->$method($input[$field]);
                }
            }
        }
        $this->update_sendmail($input);

        if ($input && $this->_form->isValid())
        {
            $user_state = $this->users->update_user_information($this->userRunning()->id, $input);
            $this->auth->connect($user_state);
            $this->session->setFlash('success', '✅ Information modified.');
        }
        elseif (!empty($input))
        {

            $this->session->setFlash('danger', 'Opsi, smthg went wrong');
			$this->_form->getErr();
        }
        Router::redirect('/settings');


    }

    public function update_username($username)
    {
        if ($this->userRunning()->login != $username)
        {
            $this->_form->isName('username', "Please enter a name without special caractere, and space.");
            $this->users->is_unique(['username'=> $username]) ? : $this->_form->setErr('username', "Your pseudo is already take.");
        } else {
            $this->_form->setErr('username', "Username not changed because current is identical.");
        }
    }

    public function update_mail($mail)
    {

        if (strcmp($this->userRunning()->mail, $mail))
        {
            $this->_form->isEmail('mail', "You're email is not valid.");
			$this->users->is_unique(['mail' => $mail]) ? : $this->_form->setErr('mail', "It seems like this mail account already exists.");

        } else {
            $this->_form->setErr('mail', "Mail not changed because current is identical.");
        }
    }

    public function update_password($password)
    {
        $this->_form->isPass('password', $password);
    }

    public function update_sendmail(&$input)
    {
        // Authorization to Sending mail ?       
        if (isset($input['sendmail']) && $this->userRunning()->sendmail == 0)       // no -> yes
            $input['sendmail'] = '1';
        elseif (!isset($input['sendmail']) && $this->userRunning()->sendmail == 1)  // yes -> no
            $input['sendmail'] = '0';
        else                                                                        // no change.
            unset($input['sendmail']);
        
    }
}
