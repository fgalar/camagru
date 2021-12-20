<?PHP

/**
 *  Manipulation of string
 **/
class	Str	{

	static function random($len) {
		$alphabet = "0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
		return substr(str_shuffle(str_repeat($alphabet, $len)), 0, $len);
	}

}
