<h1><?= "Gallery" ?></h1>
<p><?= $description; ?></p>
<div class="gallery">
<?php foreach($posts as $post) : ?>
	<figure onmouseover="display(<?=$post->photo_id?>)" onmouseout="display(<?=$post->photo_id?>)">
		<img class="photo" alt="photo" src=<?=$post->photo_path; ?>>
		<figcaption id="<?= $post->photo_id ?>"class="legende">
			<div class='icon-pack'>
				<!-- <a href="gallery/like?postId=<?= $post->photo_id; ?>" > -->
					<img onclick="like(<?= $post->photo_id ?>)" alt='like' class='icon' src='./tools/img/unlike.png'>
					<img onclick="like(<?= $post->photo_id ?>)" alt='unlike' class='icon hidden' src='./tools/img/like.png'>
				<!-- </a> -->
				<p><?= "($post->photo_nbLikes)" ?></p>
			</div>
			<div class='icon-pack'>
				<img  alt='comment' class='icon' src='./tools/img/comment.png'>
				<?= $post->photo_nbComm ?>
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
<!-- The Modal -->
<div id="myModal" class="modal">
			<span class="close">&times;</span>
			<img class="modal-content" id="img01">
			<div id="caption"></div>
</div>