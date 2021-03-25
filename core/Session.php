<?PHP

/**
 *  $session managed alert for User
 **/
class	Session	{

	public function __construct() {
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
	}

	public function setFlash($key, $msg) {
		$_SESSION['flash'][$key] = $msg;
	}

	public function flashes()	{
		return isset($_SESSION['flash']);
	}

	public function getFlashes()	{
		if (isset($_SESSION['flash'])) {
			$flash = $_SESSION['flash'];
			unset($_SESSION['flash']);
			return $flash;
		}
		return null;
	}

	public function write($key, $value)	{
		$_SESSION[$key] = $value;
	}

	public function read($key) {
		return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
	}

	public function delete($key) {
		unset($_SESSION[$key]);
	}

}
