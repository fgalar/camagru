<h1><?= $title ?></h1>

<form method="post" action="">
	<?php foreach ($form as $formElem) :?>
		<?= $formElem ?>
	<?php endforeach ;?>
</form>