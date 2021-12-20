<?PHP

	function debug($variable) {
		$debug = debug_backtrace();
		echo '<p>&nbsp;</p><a href="#"><strong>' . $debug[0]['file'] . ' </strong> l. ' . $debug[0]['line'] . '</a> ';

		echo '<ol>';
		foreach($debug as $k=>$v) {
			if ($k > 0) {
				echo '<li><strong>' . $v['file'] . ' </strong> l. ' . $v['line'] . '</li>';
			}
		}
		echo '</ol>';
		echo '<pre>' . print_r($variable, true) . '</pre>';
	}




	function random($len) {
		$alphabet = "0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
		return substr(str_shuffle(str_repeat($alphabet, $len)), 0, $len);
	}

	function create_dir($path) {
		return (!@mkdir($path, 0755) && !is_dir($path));
	}

	function securite_bdd($string)
	{
		// On regarde si le type de string est un nombre entier (int)
		if(ctype_digit($string))
		{
			$string = intval($string);
		}
		// Pour tous les autres types
		else
		{
			$string = mysql_real_escape_string($string);
			$string = addcslashes($string, '%_');
		}

		return $string;
	}
