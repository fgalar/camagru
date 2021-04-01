<h1>Bonjour, <?= $_SESSION['auth']->account_name; ?></h1>

<form method="post" action="user/account/majInfo">

	<?php foreach ($form as $formElem) : ?>
		<?= $formElem ;?>
	<?php endforeach; ?>

</form>

<?php foreach ($selfies as $selfie): ?>
	<div class="miniature">
		<span class="remove">&times;</span>
		<img alt="<?= $selfie->photo_id ?>" src="<?= $selfie->photo_path ?>" >
	</div>
<?php endforeach ; ?>