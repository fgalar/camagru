<?php

class Photobooth extends Controller {

	private $_selfie;
	private $_filter;

	public function __construct() {
		parent::__construct();
		if (!$this->userRunning())
			Router::redirect('/account/signin');
	}

	public function default() {
		$d = [  'nav_title'		=> 'Photobooth',
				'title' 		=> 'Photobooth'];
		$pictures = $this->pictures->get_all_pictures_by_user($this->auth->get_auth()->id);
		$this->set($d);
		$this->set('photos', $pictures);
		$this->render('photobooth');
	}

	public function share() {

		if (isset($_POST)) {

			$path_picture = $this->blendFilter($_POST['selfie'], $_POST['filter']);
			$new_picture = $this->pictures->add_picture($this->auth->get_auth()->id, $path_picture);
			$new_picture = json_encode($new_picture);
			print_r($new_picture);

		}

	}

	public function blendFilter($selfie, $filter) {
		$dst_img = imagecreatefrompng($selfie);
    	$src_img = imagecreatefrompng($filter);

		//create the saving pictures directory
		$dir = 'tools/img/' . $this->auth->get_auth()->login;
		create_dir($dir);
		list($width, $height) = $this->put_filter_on_usrImg($selfie, $filter, $src_img, $dst_img);
		$montage = $this->fill_black_font($dst_img, $width, $height);

		// PNG to files
		$url = $dir .  '/'. time() . '.png';
		imagepng($montage, $url, 0);

		return $url;
	}

	public function put_filter_on_usrImg($img_url, $fltr_url, $src_img, &$dst_img) {
		list($dst_width, $dst_height) = getimagesize($img_url);
		list($width, $height) = getimagesize($img_url);
		list($width_orig, $height_orig) = getimagesize($fltr_url);

		$ratio_orig = $width_orig/$height_orig;
		if ($width/$height > $ratio_orig) {
			$width = $height * $ratio_orig;
		} else {
			$height = $width / $ratio_orig;
		}
		imagecopyresampled($dst_img, $src_img,
			$dst_width/2 - $width/2, $dst_height - $height,	// dst : x, y = bottom centered
			0, 0,											// src : x, y
			$width, $height,								// dst : width, height
			$width_orig, $height_orig						// src : width, height
		);
		return [$dst_width, $dst_height];
	}

	public function fill_black_font($src, $src_width, $src_height) {
		$width = 640;
		$height = 480;
		$width_orig = $src_width;
		$height_orig = $src_height;
		$dst = imagecreatetruecolor($width, $height);

		$ratio_orig = $src_width/$src_height;
		if ($width/$height > $ratio_orig) {
			$src_width = $height * $ratio_orig;
		} else {
			$src_height = $width / $ratio_orig;
		}
		imagecopyresampled(
			$dst, $src,											// dest = black font, src = montage
			$width/2 - $src_width/2, $height/2 - $src_height/2,	// dst : x, y = bottom centered
			0, 0,												// src : x, y
			$src_width, $src_height,							// dst : width, height
			$width_orig, $height_orig							// src : width, height
		);

		return $dst;
	}

	public function delete_post()
	{
		if (isset($_POST)) {
			$post = $_POST['picture'];
			$user = $this->auth->get_auth()->id;

			$this->pictures->delete_picture($post, $user);
			unlink($post);

		}

	}

}
