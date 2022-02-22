<div id="formularies">
	<form method="post" action="<?= $action ?>">

		<?php foreach ($form as $formbox) : ?>
			<?= $formbox ;?>
		<?php endforeach; ?>

	</form>
</div>
