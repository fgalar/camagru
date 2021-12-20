<?php
class Gallery extends Controller {

	public $currentPage;

	function __construct() {
		parent::__construct();
		$this->loadModel('Photo');
		$this->loadModel('Like');
		$this->loadModel('Comment');
		$this->loadModel('Account');
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

		$user = null;
		if ((!empty($this->userRunning()) && ($user = $this->userRunning()->account_id)) || $user == null) {

			foreach ($posts as $post) {
				if (!$this->Like->isLiked($post->photo_id, $user)) {
					$post->likedByUser = 'hidden';
				} else {
					$post->unlikedByUser = 'hidden';
				}
			}
		}

		return [$nb_pages, $posts];

	}

	public function loadGallery($data) {
		$this->set(['nav_title' 	=> 'Gallery',
					'description'	=> 'Here you can see all Camagru selfies of users']);

		(!empty($data['page'])) ? $this->currentPage = $data['page']	: $this->currentPage = 1;
		(!empty($data['post'])) ? $this->currentPost = $data['post']	: 0;

		if (!$this->userRunning()) {
			 $action = 'redirect';

		 } else {
			$action = 'like';
		 }
		$this->set('act_like', $action);

	}

	public function like() {

		$post = $_POST['postId'];
		$user = $this->userRunning()->account_id;

		$this->Like->add_likeOn($post, $user);
		$this->Photo->liked($post);

	}

	public function unlike() {
		$post = $_POST['postId'];
		$user = $this->userRunning()->account_id;

		$this->Like->rmLikeOn($post, $user);
		$this->Photo->unliked($post);

	}

	public function getComments() {

		$postId = Router::getELemUri()['postId'];
		$commentary = $this->Comment->get_comments($postId);
		$this->set('jsdata', $commentary);

	}

	public function addComment() {

		$comment	= $_POST['comment'];
		$onPostId	= $_POST['photoId'];
		$userId		= $this->userRunning()->account_id;
		$author		= $this->Comment->add_comment($comment, $onPostId, $userId);

		$for		= $this->Photo->commented($onPostId);
		$for		= $this->Account->getAccountById($for->photo_userId);

		mail($for->account_mail,
			'New comment on your post',
			"You've got a new comment : '$comment' \n by $author->name",
			array(
				'From' => 'fgarault@camagru.42.fr'));
		$this->set('jsdata', $author);

	}

	public function actionLog() {
		$this->session->setFlash('danger', "You have to be log for like, or comment !");
		Router::redirect('../user/login');
		exit();
	}

}
