<?php

class About extends Controller {

	function __construct() {

		parent::__construct();
	}

	public function index() {
		$this->set(array(
			'nav_title' 	=> 'The Project',
			'description'	=> 'Little web-site Instagram like, coded in PHP. Permit users to create and share their own photomontage. Frameworks were forbidden in this project, and the web-app must compatible with Firefox (version >= 41) and Chrome ( version >= 46)',
			'title'			=> 'Camagru Project',
			'objectives'	=> array(
				'Manage users, and permissions.',
				'Mailing.',
				'Security, and data validations.'
			),
			'skills'		=> array(
				'DB & Data.',
				'Security.'
			)
		));

	}
}
