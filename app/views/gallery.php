<h2><?= $description; ?></h2>
<div class="gallery">
<?php foreach($posts->pictures as $post) : ?>
	<figure id="<?= $post->path ?>">
	<?php if ($this->auth->get_auth() && str_contains($post->path, "/".$this->auth->get_auth()->login."/")):?>
		<span id="deleteCross" alt="delete picture" class='clickable' onclick="delete_picture('<?= $post->path ?>')">␡</span>
		<div class='msg' style='display: none'>delete picture</div>
	<?php endif; ?>
		<img class="photo clickable" alt="photo" src="<?= $post->path ?>" id="<?= $post->id ?>">

		<figcaption class="legende" id="<?= 'caption' . $post->id ?>">
			<div class='icon-pack'>
				<button type=button class="icon-pack"  onclick="switch_like(<?= $post->id ?>)" <?= ($this->userRunning()) ? '' : 'disabled';?>>
					<img alt='unlike' class='icon unlike <?= ($post->liked_by_current_user)? "": "hidden"?>' src='./tools/img/like.png'>
					<img alt='like' class='icon like <?= ($post->liked_by_current_user)? "hidden": ""?>' src='./tools/img/unlike.png'>
					<p class="icon"><?= $post->nb_likes ?></p>
				</button>
			</div>
			<div class='icon-pack comment'>
			<button type=button class="icon-pack">
				<img alt='<?= $post->id ?>' class='icon com clickable' src='./tools/img/comment.png'>
				<p class="icon nbCom"><?= $post->nb_comments ?></p>
			</button>
			</div>
		</figcaption>

	</figure>
<?php endforeach; ?>
</div>
<!-- Paging -->
<?php if ($posts->end_page > 1 ):?>
<nav>
	<ul class="paging">
		<!-- Si start_page == 1 on cache 'First ...' -->
		<li class="page-item <?= ($posts->start_page == 1) ? "hidden" : ""; ?>">
			<a href="./gallery?page=1" class="page-link">First ... </a>
		</li>
		<?php for ($page = $posts->start_page; $page <= $posts->end_page; $page++): ?>
			<li class="page-item">
				<a href="./gallery?page=<?= $page; ?>" class="page-link <?= ($posts->current_page == $page) ? "active" : ""; ?>"><?= $page; ?></a>
			</li>
		<?php endfor; ?>
		<!-- Si end_page == nombre total de page cache '... Last' -->
		<li class="page-item <?= ($posts->end_page == $posts->nb_pages) ? "hidden" : ""; ?>">
			<a href="./gallery?page=<?= $posts->nb_pages; ?>" class="page-link">...Last</a>
		</li>
	</ul>
</nav>
<?php elseif (count($posts->pictures) == 0) :?>
<h3 class="empty">No picture ... </h3>
<?php endif; ?>
<!-- end Paging -->
<!-- Modal -->
<div id="myModal" class="modal">
	<div class="modal-content">
		<span class="close">&times;</span>

		<img class="image" id="img01">
		<?php if ($this->auth->get_auth()) : ?>
			<form method="POST" id='addComment'>
				<textarea id="commentArea" name="comment" placeholder=" Say something..."></textarea>
				<button id="btn_post" type="button" onclick="postComment()">Publish</button>
			</form>
		<?php endif; ?>

		<ul id="commentaries"></ul>
	</div>
</div>
