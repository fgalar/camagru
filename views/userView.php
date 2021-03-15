<h1><?= $title ?></h1>

<form method="post" action="">
	<?php foreach ($user_input as $input=>$type) : ?>
		<label for="<?= $input ;?>"> <?= ucfirst($input)?> </label>
		<input type="<?= $type ?>" name="<?= $input ?>" id="<?= $input ?>" >
		<?php if (isset($link)): ?>
			<?php if ($input === "password") :?>
				<br /><a href="user/login/resetPass"> <?= $link; ?> </a> <br />
			<?php endif;?>
		<?php endif;?>
		<br /> <br />
	<?php endforeach; ?>
	<button type="submit"><?= $Submit_btn?> </button>
</form>