<div id="formularies">
	<form method="post" action="<?= $action ?>">

		<?php foreach ($form as $formbox) : ?>
			<?= $formbox ;?>
		<?php endforeach; ?>

	</form>
</div>
<!-- TODO: REMOVE -->
<?php //foreach ($selfies as $selfie): ?>
	<!--<div class="miniature">
		<span class="remove">&times;</span>
		<img alt="<?= $selfie->photo_id ?>" src="<?= $selfie->photo_path ?>" >
	</div>-->
<?php //endforeach ; ?>