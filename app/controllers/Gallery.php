<?php
    require_once "controllers/Mail.php";

    class Gallery extends Controller
    {
        // private $_page = 1;
        protected $likes;
        protected $comments;

        public function __construct()
        {
            parent::__construct();

            $this->likes = $this->loadModel('Like');
            $this->comments = $this->loadModel('Comment');

            // if (isset($_GET['page']) && $_GET['page'] > 0)
            //     $this->_page = $_GET['page'];
        }

        public function default()
        {
            $posts = $this->get_posts();
            /*
                -> nb_page
                -> current_page
                -> start_page
                -> end_page
                [pictures]
                    -> id
                    -> path
                    -> [likes]
                        -> req : current_user liked pictures
                        -> req: count(*) nb likes
            */

            $this->set(['nav_title' 	=> 'Gallery',
					    'description'	=> 'Wall of Fame.']);
            $this->set('posts', $posts);

            $this->render('/gallery');
        }

        public function get_posts()
        {
            $posts = new stdClass();
            $limit = 9;
            // $offset = ($limit * $page) - $limit;
            // $this->pagination($posts, $limit);

            $posts->{'pictures'} = $this->pictures->get_limited_posts(['id', 'path'], $limit);

            foreach ($posts->pictures as $picture)
            {
                $picture->{'nb_likes'} = $this->likes->count_picture_likes($picture->id);
                if ($current_user = $this->auth->get_auth())
                    $picture->{'liked_by_current_user'} = $this->likes->is_current_user_liked($picture->id, $current_user);
                else
                    $picture->{'liked_by_current_user'} = false;
                $picture->{'nb_comments'} = $this->comments->count_picture_comments($picture->id);
                // $picture->{'comment'} = $this->comments->get_comments_for_post($picture->id);
            }
            return ($posts);
        }

        // public function pagination($posts, $limit)
        // {
        //     $total_posts = $this->pictures->count();
        //     $currentPage = $this->_page;
		// 	$totalPage = ceil($total_posts / $limit);

		// 	$startPage = $currentPage - 4;
		// 	$endPage = $currentPage + 4;

		// 	if ($startPage <= 0) {
        //         $endPage -= $startPage - 1;
		// 		$startPage = 1;
		// 	}

		// 	if ($endPage > $totalPage)
        //     $endPage = $totalPage;

        //     $posts->{'nb_pages'} = $totalPage;
        //     $posts->{'current_page'} = $currentPage;
        //     $posts->{'start_page'} = $startPage;
        //     $posts->{'end_page'} = $endPage;
        // }

        public function like()
        {
            $post = $_POST['picture'];
            $user = $this->auth->get_auth()->id;

            $this->likes->add_like($post, $user);
        }

        public function unlike()
        {
            $post = $_POST['picture'];
            $user = $this->auth->get_auth()->id;

            $this->likes->delete_like($post, $user);
        }

        public function get_comments()
        {
            $post = $_POST['picture'];
            $comments = $this->comments->get_comments_for_post($post);
            $comments = json_encode($comments);
            print_r($comments);
        }

        public function add_comment()
        {
            $user = $this->auth->get_auth();

            $comment = htmlspecialchars($_POST['comment'], ENT_QUOTES);
            $picture = $_POST['picture_id'];

            $new_comment = $this->comments->add_comment($user->id, $picture, $comment);
            $user_posting = $this->pictures->get_author_picture($picture);

            // Send mail to user posting this picture.
            if ($user_posting->sendmail)
            {
                $mail = New Mail();
                $mail->new_comment_on_post($user_posting, $comment, $user->login);
            }

            $new_comment = json_encode($new_comment);
            print_r($new_comment);
        }

        public function get_posts_before_id() {
            /* _____ Return Object _____
                [pictures]
                    -> id
                    -> path
                    -> [likes]
                        -> current_user liked pictures
                        -> nb likes
            */
            if ($_POST) {
                $posts = new stdClass();
                $nb_require = $_POST['nb'];
                $last_id = $_POST['last_id'];

                $posts->{'pictures'} = $this->pictures->get_limited_posts_before_id(['id', 'path'], $last_id, $nb_require);

                foreach ($posts->pictures as $picture)
                {
                    $picture->{'nb_likes'} = $this->likes->count_picture_likes($picture->id);
                    if ($current_user = $this->auth->get_auth())
                        $picture->{'liked_by_current_user'} = $this->likes->is_current_user_liked($picture->id, $current_user);
                    else
                        $picture->{'liked_by_current_user'} = false;
                    $picture->{'nb_comments'} = $this->comments->count_picture_comments($picture->id);
                }

                $posts = json_encode($posts);
                print_r($posts);
            }
        }

    }