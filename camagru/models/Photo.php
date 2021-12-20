<?php

class Photo extends Model {

	function __construct() {
		parent::__construct();
	}

	/**
	* @param int $limit = img displayed
	* @param int $offset = before
	* @return tab** of 9
	**/

	function get_news($limit, $offset) {

		$ret = $this->query("SELECT * FROM Camagru. `photos` ORDER BY `photo_takeAt` DESC LIMIT :nPost OFFSET :fPost", [':nPost' => $limit, ':fPost' => $offset], 'i')->fetchAll();
		return $ret ;
	}

	function getPostUser($id) {
		$req = $this->find([
			'conditions' => 'photo_userId = ?',
			'params'	=> [$id]]);
		return $req;
	}

	function getImgbyId($id) {
		$req = $this->find([
			'conditions' => 'photo_id = ?',
			'params'	=> [$id]])->fetch();
		return $req;
	}

	function get_photo_count() {
		$count = $this->query("SELECT COUNT(*) AS nb_posts FROM Camagru.photos")->fetch();
		$count = (int)$count->nb_posts;
		return $count;
	}

	function add($url, $id) {
		$this->set(['to_set'	=> ['photo_path = ?',
									'photo_takeAt = NOW()',
									'photo_userId = ?'],
					'params'	=> [$url, $id]]);
	}

	function liked($post) {
		$this->update([
			'to_update'	=> 'photo_nbLikes = photo_nbLikes + 1',
			'conditions'=> 'photo_id = ?',
			'params'	=> [$post]
		]);
	}

	function unliked($post) {
		$this->update([
			'to_update'	=> 'photo_nbLikes = photo_nbLikes - 1',
			'conditions'=> 'photo_id = ?',
			'params'	=> [$post]
		]);
	}

	function commented($post) {

		$this->update([
			'to_update'	=> 'photo_nbComm = photo_nbComm + 1',
			'conditions'=> 'photo_id = ?',
			'params'	=> [$post]
		]);
		$user = $this->find([
			'conditions'	=> 'photo_id = ?',
			'params'		=> [$post]
		])->fetch();
		return $user;
;
	}

	function removePhoto($id) {

		$this->del([
			'conditions' => 'photo_id = ?',
			'params'	=> [$id]
		]);
	}

}