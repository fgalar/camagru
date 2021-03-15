<h1>Bonjour, <?= $_SESSION['auth']->account_name; ?></h1>

<form method="post" action="user/account/majInfo">

	<?php foreach ($user_input as $input=>$type) : ?>
		<label for="<?= $input ;?>"> <?= ucfirst($input)?> </label>

		<?php if (strcmp('password', $type)) : ?>
			<?php $tmp = 'account_' . $input;?>
			<?php $tmp = $_SESSION['auth']->$tmp ;?>
		<?php else : ?>
				<?php $tmp = ''; ?>
		<?php endif; ?>
		<input type="<?= $type ?>" name="<?= $input ?>" id="<?= $input ?>"value="<?= $tmp ; ?>" />
		<br /> <br />
	<?php endforeach; ?>
	<button type="submit"><?= $submit_btn?> </button>
</form>