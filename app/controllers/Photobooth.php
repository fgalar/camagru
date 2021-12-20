<?php

class Photobooth extends Controller {

	private $_selfie;
	private $_filter;

	public function __construct() {
		parent::__construct();
		if (!$this->userRunning())
			$this->not_authorized();
	}

	public function default() {
		$d = [  'nav_title'		=> 'Photobooth',
				'title' 		=> 'Photobooth'];
		$this->set($d);
		$this->render('photobooth');
	}

	public function share() {

		if (isset($_POST)) {

			$path_picture = $this->blendFilter($_POST['selfie'], $_POST['filter']);
			$this->pictures->add_picture($this->auth->get_auth()->id, $path_picture);
		}

	}

	public function blendFilter($selfie, $filter) {

		list($dst_width, $dst_height) =  getimagesize($selfie);
		list($src_width, $src_height) =  getimagesize($filter);
		$dst_img = imagecreatefrompng($selfie);
    	$src_img = imagecreatefrompng($filter);

		//create the saving pictures directory
		$dir = 'tools/img/' . $this->auth->get_auth()->login;
		create_dir($dir);

		// Copy and resize part of an image with resampling
		imagecopyresampled(
			$dst_img, $src_img,
			0, 0, 0, 0, # dst_x, dst_y, src_x, src_y
			$dst_width, $dst_height,
			$src_width, $src_height
		);

		// Output a PNG to a files
		$url = $dir .  '/'. time() . '.png';
		imagepng($dst_img, $url, 0);

		return $url;
	}

	public function blendFilter2($selfie, $filter) {

	}

}
