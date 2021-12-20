<?php

class Comment extends Model {

	function __construct() {
		parent::__construct();

	}

	function get_comments($post) {
		$comments = $this->find([
			'conditions' => "`comm_forPhoto` = ? ORDER BY `comm_writeAt`",
			'params'	=> [$post]
		])->fetchAll();
		foreach ($comments as $obj) {
			$obj->name = $this->get_authorComment($obj->comm_byId);
		}
		return $comments;

	}

	function get_authorComment($id) {
		$ret = $this->query("SELECT account_name FROM `accounts` WHERE `account_id` = ? ", [$id])->fetch();
		return $ret->account_name;
	}

	function add_comment($comment, $onPostId, $userId) {

		$this->set(['to_set' => ['comm_content = ?',
								'comm_byId = ?',
								'comm_forPhoto = ?'],
					'params' => [$comment,$userId, $onPostId]]);
		$lastCom = $this->find([
			'conditions'	=> 'comm_id = ?',
			'params'		=> [$this->lastInsertId()]
		])->fetch();
		$lastCom->name = $this->get_authorComment($lastCom->comm_byId);
		return $lastCom;
	}

}