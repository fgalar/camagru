var tableImg	= document.querySelectorAll(".photo");
var com_icons_t = document.querySelectorAll('.icon-pack.comment');
var modal		= document.getElementById("myModal");
var listComments= document.getElementById('commentaries');
var modalImg = document.getElementById('img01');
var close = document.getElementsByClassName("close")[0];
var imgID		= 0;

var gallery = document.querySelector('#gallery')

// Open modal on photo click
	function focusImg(photo) {

		modal.style.display = "block";
		modalImg.src = photo.src;
		modalImg.value = photo.id;
		imgID = photo.id;
		get_comments(photo.id);

	}

	tableImg.forEach(img => {

		img.addEventListener('click', function() {

			img.onclick = focusImg(img);

		});

	});

	com_icons_t.forEach(com_icon => {

		com_icon.addEventListener('click', function() {

			com_icon.onclick = focusImg(com_icon.parentNode.parentNode.parentNode.getElementsByTagName('img')[0]);

		});

	})


	close.addEventListener('click', function() {

		while (listComments.lastElementChild) {

			listComments.removeChild(listComments.lastElementChild);

		}
		modal.style.display = "none";

	});


// actions
	function switch_like(id) {

		var post = document.getElementById('caption' + id);
		var like = post.getElementsByClassName('like')[0];
		var unlike = post.getElementsByClassName('unlike')[0];
		var nb = post.getElementsByTagName('p')[0];

		if (like.classList.contains('hidden')){

			postPHP('gallery/unlike', {picture: id});
			nb.innerHTML = parseInt(nb.innerHTML) - 1;

		} else if (unlike.classList.contains('hidden')) {

			postPHP('gallery/like', {picture: id});
			nb.innerHTML = parseInt(nb.innerHTML) + 1;

		}
		like.classList.toggle('hidden');
		unlike.classList.toggle('hidden');

	}

	function get_comments(post_id) {

		postPHP('gallery/get_comments', {picture: post_id}, function(comments) {

			comments.forEach(comment => {
				list_elem = document.createElement('li');
				list_elem.innerHTML = "<h1>"+ comment.login + " <i>" + comment.timestamp + "</i>" + "</h1>" + "<p>" + comment.comment + "</p><hr>";
				listComments.insertAdjacentElement('beforeend', list_elem);
			});

		});

	}

	function postComment() {

		content = document.getElementsByTagName('textarea')[0].value;
		postPHP('gallery/add_comment', {picture_id: imgID, comment: content}, function(new_comment){

			// add with other comment
			new_list_element = document.createElement('li');
			document.getElementsByTagName('textarea')[0].value = null;
			new_list_element.innerHTML = "<h1>"+ new_comment.login + " <i>" + new_comment.timestamp + "</i>" + "</h1>" + "<p>" + new_comment.comment + "</p><hr>";
			listComments.insertBefore(new_list_element, listComments.firstChild);

			// +1 comment icon
			var post = document.getElementById('caption' + imgID);
			var com = post.getElementsByClassName('nbCom')[0];
			com.innerHTML = parseInt(com.innerHTML) + 1;

		})

	}

// infinite scroll
window.addEventListener('scroll', () => {
	// scrollTop : ce qu'on a scrollé depuis le top
	// scrollHeight : La hauteur total qui peut etre scroller par le client.
	// clientHieght : Hauteur visible du screen du client
	const {scrollTop, scrollHeight, clientHeight} = document.documentElement;

	if (clientHeight + scrollTop === scrollHeight) {
		add_pictures(3);
	}
})

function add_pictures(nb) {
	var last_id = get_page_last_picture();

	postPHP('gallery/get_posts_before_id', { 'last_id': last_id, 'nb': nb },
			(new_element) => {
				if (typeof(new_element) !== 'undefined')
				{
					nb = new_element.pictures.length;
					// console.log(new_element.pictures.length)
					for (let i = 0; i < nb; i++) {
						var figure = document.querySelector('figure').cloneNode([true]);
						figure.setAttribute('id', new_element.pictures[i].path);

						img = figure.querySelector('img');
						img.setAttribute('id', new_element.pictures[i].id);
						img.src = new_element.pictures[i].path;
						img.addEventListener('click', function() {
							img.onclick = focusImg(img);
						});

						figcaption = figure.querySelector('figcaption');
						figcaption.setAttribute('id', "caption"+ new_element.pictures[i].id);

						button = figcaption.querySelector('button');
						button.setAttribute('onclick', "switch_like("+ new_element.pictures[i].id +")");

						if (typeof(new_element.pictures[i].liked_by_current_user) !== 'undefined') {
							like = button.querySelector('.like');
							unlike = button.querySelector('.unlike');
							if (new_element.pictures[i].liked_by_current_user == false) {
								like.setAttribute('class', 'icon like');
								unlike.setAttribute('class', 'icon unlike hidden');
							} else if (new_element.pictures[i].liked_by_current_user == true) {
								like.setAttribute('class', 'icon like hidden');
								unlike.setAttribute('class', 'icon unlike');
							}
						}

						nb_like = button.querySelector('p');
						nb_like.innerHTML = new_element.pictures[i].nb_likes;

						comment = figcaption.querySelector('.com');
						comment.setAttribute('alt', new_element.pictures[i].id);

						nb_comment = figcaption.querySelector('.nbCom');
						nb_comment.innerHTML =  new_element.pictures[i].nb_comments

						gallery.appendChild(figure);
					}
				}
	})

}

function get_page_last_picture() {
	var images = document.querySelectorAll('.photo');
	return images[images.length - 1].id;
}