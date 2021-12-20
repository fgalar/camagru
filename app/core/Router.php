<?PHP

class Router {

	static function parse(){
		// si url == null => gallery
		// si action == null
		$url = $_GET ? $_GET['url'] : 'gallery';
		$params = explode('/', $url);
		$define = array(
			'controller'	=> $params[0],
			'action' 		=> isset($params[1]) ? $params[1] : 'default'
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
?>
