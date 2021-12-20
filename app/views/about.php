<div id="about">
	<h2><?= $title?></h2>
	<p><?= $description?></p>
	<ul>
		<h3>Objectives:</h3>
		<?php foreach($objectives as $obj) : ?>
			<li><?= $obj ?></li>
		<?php endforeach; ?>
	</ul>
	<ul>
		<h3>Skills:</h3>
		<?php foreach($skills as $skill) : ?>
			<li><?= $skill ?></li>
		<?php endforeach; ?>
	</ul>
</div>