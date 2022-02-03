<?php
    class Form extends Controller {
        // Générer un formulaire
        // Optenir les saisies d'un formulaire
        // check validation

        private $_errors = [];

        public function __construct() {
            parent::__construct();
        }

    # HTML generator
        public function input($name, $displayed = null, $type = "text", $placeholder = null) {
            $dom = new DOMDocument('1.0');
            
            $label = $this->create_label($dom, $displayed, $name);
            $input = $this->create_input($dom, $type, $name, $placeholder);

            
            $label->appendChild($input);
            $dom->appendChild($label);

            $br = $dom->createElement('br');
            $br2 = $dom->createElement('br');
            $dom->appendChild($br);
            $dom->appendChild($br2);

            return $dom->saveHTML();
        }

        public function checkbox($name, $displayed, $placeholder) {
            $dom = new DOMDocument('1.0');
            // <label for="sendmail"> blabla
            //      <label class='switch'>
            //          <input name="sendmail" type="checkbox" CHECKED>
            //          <span class='slider round'> 
            //      </label>

            $g_label = $this->create_label($dom, $displayed, $name);
            $s_label = $this->create_label($dom, null, null, 'switch');
            $g_label->appendChild($s_label);

            $input = $this->create_input($dom, 'checkbox', $name);

            if ($placeholder) {
                $checked = $dom->createAttribute('checked');
                $input->appendChild($checked);
            }
            
            $span = $dom->createElement('span');
            $class = $dom->createAttribute('class');
            $class->value = 'slider round';
            $span->appendChild($class);

            $br = $dom->createElement('br');
            $br2 = $dom->createElement('br');

            $s_label->appendChild($input);
            $s_label->appendChild($span);
            $dom->appendChild($g_label);

            $dom->appendChild($br);
            $dom->appendChild($br2);
            return $dom->saveHTML();
        }

        public function create_label(&$dom, $display = null, $for = null, $class = null)
        {
            $label = $dom->createElement('label', $display);
            if ($for !== null)
            {
                $att_for = $dom->createAttribute('for');
                $att_for->value = $for;
                $label->appendChild($att_for);
            }
            if ($class !== null)
            {
                $att_class = $dom->createAttribute('class');
                $att_class->value = $class;
                $label->appendChild($att_class);
            }

            return $label;
        }

        public function create_input(&$dom, $type, $name, $placeholder = null)
        {
            $input = $dom->createElement('input');
            $att_type = $dom->createAttribute('type');
            $att_type->value = $type;
            $input->appendChild($att_type);
            if ($name !== null)
            {
                $att_name = $dom->createAttribute('name');
                $att_name->value = $name;
                $input->appendChild($att_name);
            }
            if ($placeholder)
            {
                $att_placeholder = $dom->createAttribute('placeholder');
                $att_placeholder->value = $placeholder;
                $input->appendChild($att_placeholder);
            }

            return $input;
        }

        public function submit($name) {
            $submit = "";

            if ($name == 'Login')
                $submit = "<a href='account/reset_form_send_mail'>Password forgotten</a> <br/>";
            $submit .= " <button type='submit'> $name </button>";
            return $submit;
        }    




    # Check input
        public function isValid() {
            return empty($this->_errors);
        }

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
			if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z0-9-]{8,64}$/", $pass)) {
				$this->_errors[$field] = "Password must contain a number and at least 8 no special characters. An uppercase, and a number.";
			}
		}

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

        public function check_input($input) {
            $this->isName('username', "Please enter a name without special caractere, and space.");
            $this->users->is_unique(['username'=> $input['username']]) ? : $this->setErr('username', "Your pseudo is already take.");

            $this->isEmail('mail', "You're email is not valid.");
            $this->users->is_unique(['mail' => $input['mail']]) ? : $this->setErr('mail', "It's seems like you have already a account here.");

            $this->isPass('password', $input['password']);
            return $this->isValid();
        }
    }