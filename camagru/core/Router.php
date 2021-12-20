<?PHP

class Router {

	static function parse(){
		/*
			return : [[controller] => 'home', [action] => index, [params] => []]
			Si contoller est null, controller == home
			Si params est null, action == index
		*/
		$url = $_GET ? $_GET['url'] : 'home';
		$params = explode('/', $url);
		$define = array(
			'controller'	=> $params[0],
			'action' 		=> isset($params[1]) ? $params[1] : 'index'
		);
		$define['params'] = array_slice($params, 2);
		return $define;
	}

	static function getWebRoute() {
		return str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
	}

	static function redirect($page) {
		header("Location: $page");
	}

	static function getELemUri() {
		$uri = $_SERVER['REQUEST_URI'];
		$getter = parse_url($uri, PHP_URL_QUERY);

		if (!isset($getter)) { return null; }

		$getter = explode('&', $getter);

		foreach($getter as $elem) {
			$ret = explode('=', $elem);
			$tab[$ret[0]] = $ret[1];
		}
		return ($tab);
	}
}
