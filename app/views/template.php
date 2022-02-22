<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<base href="<?= Router::getWebRoute() ; ?> ">
		<!-- Camagru Icon -->
		<link rel="icon" href="./tools/img/icon.png">
		<!-- My Stylesheets -->
		<link rel="stylesheet" type="text/css" href="./tools/css/style.css">
		<!-- Élément spécifique -->
		<title><?= $nav_title ?></title>
		<!-- Fin d'Élément spécifique -->
	</head>
	<body>

		<!-- <header id='dataBox'>
			<?php if (isset($jsdata)): ?>
				<?= json_encode($jsdata); ?>
			<?php endif; ?>
		</header> -->

		<header id='alert'>
			<?php if (!empty($flash)): ?>
			<?php foreach($flash as $type => $message): ?>
					<div class="alert alert-<?= $type;?>">
					<?= $message; ?>
					</div>
			<?php endforeach; ?>
			<?php endif; ?>
		</header>

		<header class="menu">
			<div class="logo">
				<h1>Camagru</h1>
				<img src="./tools/img/animCam.gif" alt="gif">
			</div>
			<div class="menuToggle">
				<span></span>
			</div>

			<nav class="nav">
				<ul>
					<li><a href="gallery">gallery</a></li>
					<li><a href="about">about</a></li>
					<?php if ($this->userRunning()): ?>
						<li><a href="photobooth">photobooth</a></li>
						<li><a href="settings">settings</a></li>
						<li><a href="account/signout">log-out</a></li>
					<?php else: ?>
						<li><a href="account/signup">register</a></li>
						<li><a href="account/signin">log-in</a></li>
					<?php endif; ?>
				</ul>
			</nav>

		</header>

		<!-- Élément spécifique -->
		<div id="content" style="text-align:center">
			<?= $content ?>
		</div>
		<!-- Fin d'élément spécifique -->
		<footer>
			<p>&copy Fanny GARAULT-LARPENT - 2021</p>
		</footer>

	</body>

	<script type="text/javascript" src="./tools/js/buttonAnim.js"></script>
	<script type="text/javascript" src="./tools/js/postRequest.js"></script>
	<?php if($nav_title === "Gallery"): ?>
		<script type="text/javascript" src="./tools/js/gallery.js"></script>
	<?php endif; ?>
	<?php if($nav_title === "Photobooth"): ?>
		<script type='text/javascript' src='./tools/js/photobooth.js'></script>
	<?php endif; ?>
	<?php if($nav_title === "Profil"): ?>
		<script type='text/javascript' src='./tools/js/account.js'></script>
	<?php endif; ?>
</html>
