<h1><?= $title ?></h1>

<form method="post" action=<?= $action ?>>
	<?php foreach ($form as $formbox) :?>
		<?= $formbox ?>
	<?php endforeach ;?>
</form>