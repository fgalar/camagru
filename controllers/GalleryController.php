<?php
class Gallery extends Controller {

	public $currentPage;

	function __construct() {
		parent::__construct();
		$this->loadModel('Photo');
		$this->loadModel('Like');
		$data = Router::getELemUri();
		$this->loadGallery($data);

	}

	public function index() {

		list($nb_pages, $posts) = $this->pageManager();
		$this->set('pages', $nb_pages);
		$this->set('posts', $posts);

		$this->set('currentPage', $this->currentPage);



	}

	public function pageManager() {

		$nb_posts = $this->Photo->get_photo_count();
		$posts_onPage = 10;

		$nb_pages = ceil($nb_posts / $posts_onPage);

		$fpost = ($this->currentPage * $posts_onPage) - $posts_onPage;
		$posts = $this->Photo->get_news($posts_onPage, $fpost);

		return [$nb_pages, $posts];

	}

	public function loadGallery($data) {
		$this->set(['nav_title' 	=> 'Gallery',
					'description'	=> 'Here you can see all Camagru selfies of users']);

		(!empty($data['page'])) ? $this->currentPage = $data['page']	:
		$this->currentPage = 1;
		(!empty($data['post'])) ? $this->currentPost = $data['post']	: 0;
		(!$this->userRunning()) ? $action = 'redirect': $action = 'like';
		$this->set('act_like', $action);

	}

	public function like() {

		$post = $_POST['postId'];
		$user = $this->userRunning()->account_id;

		$this->Like->add_likeOn($post, $user);
		$this->Photo->liked($post, $user);
	}

	public function unlike() {
		$post = $_POST['postId'];
		$user = $this->userRunning()->account_id;

		$this->Like->rmLikeOn($post, $user);
		$this->Photo->unliked($post, $user);
	}

	public function actionLog() {
		$this->session->setFlash('danger', "You have to be log for like, or comment !");
		Router::redirect('../user/login');
		exit();
	}



}
