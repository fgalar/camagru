<?php

class Form {

	private $_data;

	public function __construct($data = null) {
		$this->_data = $data;
	}

	public function input($name, $type = "text", $displayed = null) {
		if ($displayed == null) {
			$displayed = ucfirst($name);
		}
		if (isset($this->_data)) {
			list($label, $input) = $this->predefined_input($name, $type, $displayed);
		} else {
			$label = "<label for='$name'> $displayed: </label>";
			$input = "<input name='$name' type='$type' autocomplete='off'><br/>";
		}

		if ($name == 'password') {
			$input .= "<a href='user/login/resetPass'> Password forgotten </a> <br/>";
		}
		return $label . $input . '<br/>';
	}

	public function submit($name) {
		return " <button type='submit'> $name </button>";
	}

	private function predefined_input($name, $type, $displayed) {
		$value = '';
		if ($type === 'checkbox') {
			($this->_data->account_acceptMail == '1') ? $value = 'checked': 0;
		} else if ($type !== 'password'){
			$value = "value='{$this->_data->{'account_' . $name}}'";
		}

		$label = "<label for='$name'> $displayed: </label>";
		$input = "<input name='$name' type='$type' autocomplete='on' $value><br/>";
		return [$label, $input];
	}

}