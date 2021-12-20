<?php

class Like extends Model {

	function __construct() {
		parent::__construct();
	}


	function isLiked($post, $id) {
		$ret = $this->find([
			'conditions'	=> ['like_photoId = ?',
								'like_userId = ?'],
			'params'		=> [$post, $id]
		])->fetch();
		return $ret;
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