<h1><?= "Gallery" ?></h1>
<p><?= $description; ?></p>
<div class="gallery">
<?php foreach($posts as $post) : ?>
	<figure onmouseover="mouseHoverDisplay(<?php echo $post->photo_id; ?>)" onmouseout="mouseHoverDisplay(<?php echo $post->photo_id; ?>)">
		<img class="photo" alt="photo" src="<?= $post->photo_path; ?>" id="<?= $post->photo_id ?>">

		<figcaption class="legende" id="<?= 'caption' . $post->photo_id ?>">
			<div class='icon-pack'>

					<img alt='unlike' class='icon unlike <?= $post->likedByUser ?>' src='./tools/img/like.png' onclick="unlike(<?= $post->photo_id ?>)">
					<img alt='like' class='icon like <?= $post->unlikedByUser ?>' src='./tools/img/unlike.png' onclick="<?= $act_like . '(' . $post->photo_id . ')' ?>">

				<p><?= $post->photo_nbLikes ?></p>

			</div>
			<div class='icon-pack'>
				<img alt='<?= $post->photo_id ?>' class='icon com' src='./tools/img/comment.png'>
				<p><?= $post->photo_nbComm ?></p>
			</div>
		</figcaption>

	</figure>
<?php endforeach; ?>
</div>

<nav>
	<ul class="pagination">
		<!-- <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
			<a href="./gallery/?page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
		</li> -->
		<?php for($page = 1; $page <= $pages; $page++): ?>
			<li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
				<a href="./gallery?page=<?= $page ?>" class="page-link"><?= $page ?></a>
			</li>
		<?php endfor ?>
		<!-- <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
			<a href="./gallerypage=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
		</li> -->
	</ul>
</nav>
<!-- Modal -->
<div id="myModal" class="modal">
	<span class="close">&times;</span>

	<img class="modal-content" id="img01">
	<?php if ($this->userRunning()) : ?>
	<form method="POST">
		<textarea name="comment" placeholder="Say something..."></textarea>
		<button type="button" onclick="postComment()">Publish</button>
	</form>
	<?php endif; ?>

	<ul id="commentaries">

	</ul>

	<!-- <div id="caption"></div> -->
</div>
