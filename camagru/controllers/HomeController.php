<?php
class gallery extends Controller{

	function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->set(['nav_title' => 'Camagru',
					'title'		=> 'Camagru']);
		$this->render('gallery');
	}
}