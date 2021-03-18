<?php

class Like extends Model {

	function __construct() {
		parent::__construct();
	}


	function get_nbLikesOnPhoto($post) {

	}

	function add_likeOn($post, $id) {
		$this->set([
			'to_set'	=> ['like_photoId = ?',
							'like_userId = ?'],
			'params'	=> [$post, $id]
		]);

	}

	function rmLikeOn($post, $user) {

		$this->del([
			'conditions'	=>
				['like_photoId = ?',
				'like_userId = ?'],
			'params'		=>
				[$post, $user]
		]);

	}


}