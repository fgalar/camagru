<?PHP

class Photobooth extends Controller {

	private $_selfie;
	private $_filter;

	public function __construct() {
		parent::__construct();
		if (!$this->userRunning()) {
			$this->session->setFlash('danger', "You must have an account for access this page.");
			Router::redirect('user/login');
			exit(-1);
		}
		$this->loadModel('Photo');

	}

	public function index() {
		$d = [  'nav_title'		=> 'Photobooth',
				'title' 		=> 'Photobooth'];
		$this->set($d);
	}

	public function share() {

		if (isset($_POST)) {

			$photo = $this->blendFilter($_POST['selfie'], $_POST['filter']);
			$this->Photo->add($photo, $this->userRunning()->account_id);
		}

	}

	public function blendFilter($selfie, $filter) {

		$dest = imagecreatefrompng($selfie);
    	$src = imagecreatefrompng($filter);
		$url = 'tmp/' . time() . '.png';
		list($width, $height) =  getimagesize($selfie);

		imagecopyresampled(
			$dest,
			$src,
			0, 0, 0, 0,
			$width, $height,
			500, 375);

		imagepng(
			$dest,
			$url,
			0);

		return $url;
	}

}
