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
		<title><?= $nav_title ?></title>	 <!-- Élément spécifique -->
	</head>
	<body>

		<header>
			<?php if (isset($jsdata)): ?>
				<?= json_encode($jsdata) ; ?>
			<?php endif; ?>
		</header>

		<header id='alert'>
			<?php if (!empty($flash)): ?>
			<?php foreach($flash as $type => $message): ?>
					<div class="alert alert-<?= $type;?>" >
					<?= $message; ?>
					</div>
			<?php endforeach; ?>
			<?php endif; ?>
		</header>

		<header>
			<div class="menuToggle">
			<span></span>
			</div>

			<nav class="menu nav">
				<ul>
					<li><a href="home">home</a></li>
					<li><a href="gallery">gallery</a></li>
					<li><a href="about">about</a></li>
					<?php if ($this->userRunning()): ?>
					<li><a href="photobooth">Photobooth</a></li>
					<li><a href="user/account">Account</a></li>
					<li><a href="user/logout">Log-out</a></li>
					<?php else: ?>
					<li><a href="user/register">Register</a></li>
					<li><a href="user/login">Log-in</a></li>
					<?php endif; ?>
				</ul>
			</nav>

		</header>

		<div id="content" style="text-align:center">
		<?= $content ?>	 <!-- Élément spécifique -->
		</div>

		<footer>
			<p>&copy Fanny GARAULT-LARPENT - 2021</p>
		</footer>
	</body>

	<script type="text/javascript" src="./tools/js/buttonAnim.js"></script>
	<?php if($nav_title === "Gallery"): ?>
		<script type="text/javascript" src="./tools/js/gallery.js"></script>
	<?php endif; ?>
	<?php if($nav_title === "Photobooth"): ?>
		<script type='text/javascript' src='./tools/js/cam.js'></script>
	<?php endif; ?>
</html>
