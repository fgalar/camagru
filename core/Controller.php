<?PHP

class Controller {

	private $vars 		= array(); // Var to pass on View
	private $rendered 	= false;   // True if Render already made
	protected $session;

	function __construct() {
		$this->session = new Session();
		$this->reconnectFromCookie();
	}

	/**
	 * Permit to render View
	 * @param string name of the view
	 **/
	function render ($view) {
		if ($this->rendered)
			return ;
		extract($this->vars);
		ob_start();
		require('./views/' . $view . 'View.php');
		if ($this->session->Flashes()) {$flash = $this->session->getFlashes();}
		$content = ob_get_clean();
		require("./views/templateView.php");
		$this->rendered = true;
	}

	/**
	 * Permit set one or most var to View
	 * @param $key name of variable OR tab of variables
	 * @param $value Valeur attaché à $key
	 */
	function set ($key, $value = null){
		if (is_array($key)) {
			$this->vars += $key;
		} else {
			$this->vars[$key] = $value;
		}
	}

	/**
	 * Permit to load a model
	 * @param $name of model
	 */
	function loadModel($name) {
		require_once ("./models/$name.php");

		if (!isset($this->$name)){
			$this->$name = new $name();
		}
	}

	function e404($msg) {
		header("HTTP/1.0 404 Not Found");
		$d = ['nav_title'=> '404 not found',
			  'msg'	=> $msg];
		$this->set($d);
		$this->render('404');
		die();
	}

	public function userRunning() {
		if ($this->session->read('auth'))	{
			return $this->session->read('auth');
		}
		return false;
	}

	public function reconnectFromCookie() {
		if (isset($_COOKIE['remember']) && !$this->userRunning()) {
			$remember_token = $_COOKIE['remember'];
			$parts = explode('==', $remember_token);
			$user_id = $parts[0];
			$this->loadModel('Account');
			$user = $this->Account->find([
				'conditions'	=> 'account_id = ?',
				'params'		=> [$user_id]])->fetch();
			if ($user) {
				$expected = $user_id . '==' . $user->account_tokenRememberMe . sha1($user_id);
				if ($expected == $remember_token) {
					$this->session->write('auth', $user);
					setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7, rtrim(Router::getWebRoute(), '/'));
				} else {
					setcookie('remember', NULL, -1);
				}
			}else {
				setcookie('remember', NULL, -1);
			}
		}
	}

}
