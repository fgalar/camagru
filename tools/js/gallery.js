// Displaying date when mouse hover

function mouseHoverDisplay(id) {
	var figcaption = document.getElementById('caption' + id);
	figcaption.classList.toggle('active');
}

// Open modal on photo click

var tableImg	= document.querySelectorAll(".photo");
var modal		= document.getElementById("myModal");
var modalImg	= document.getElementById('img01');
var span		= document.getElementsByClassName("close")[0];
var comment		= document.getElementById('commentaries');
var postId		= 0;

function get_comments(postId) {

	xhr = new XMLHttpRequest();

	xhr.open('POST', 'gallery/getComments?postId=' + postId , true);
	xhr.onreadystatechange = function() {
		// Control states of resquest
		if (this.readyState == 4 && this.status == 200) {
			var data = JSON.parse(this.response.getElementById('dataBox').textContent);
			while (comment.firstChild) {
				comment.removeChild(comment.firstChild);
			}
			data.forEach(function (element) {

				const elem = document.createElement('li');
				elem.innerHTML = "<h1>"+ element.name + " <i>" +element.comm_writeAt + "</i>" + "</h1>" + "<p>" + element.comm_content + "</p><hr>";
				comment.insertBefore(elem, comment.firstChild);

			});
		} else {
			return -1;
		}
	}
	xhr.responseType= 'document';
	xhr.send();

}

function focusImg(photo) {
	photo.onclick = function() {
		modal.style.display = "block";
		modalImg.src = photo.src;
		modalImg.value = photo.id;
		postId = photo.id;

		get_comments(photo.id);
	}
}

tableImg.forEach(function(element) {
	element.addEventListener("click", function() {
		focusImg(this);
	});
});


span.addEventListener('click', function() {
	modal.style.display = "none";
});

// Like

function begForALike(data, action) {
	xhr = new XMLHttpRequest();


	xhr.open('POST', action, true);
	//xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {

		// Control states of resquest
		if (this.readyState == 4 && this.status == 200) {
			console.log('Request done!');
		} else {
			console.log('ReadyState: ' + this.readyState + "\n" + 'Status :' + this.status);
		}

	}

	xhr.send('postId=' + data);
}

function like(id) {

	begForALike(id, 'gallery/like');

	var post = document.getElementById('caption' + id);
	var like = post.getElementsByClassName('like')[0];
	var unlike = post.getElementsByClassName('unlike')[0];
	var nb = post.getElementsByTagName('p')[0];

	nb.innerHTML = parseInt(nb.innerHTML) + 1;
	like.classList.toggle('hidden');
	unlike.classList.toggle('hidden');

}

function unlike(id) {

	begForALike(id, 'gallery/unlike');

	var post = document.getElementById('caption' + id);
	var like = post.getElementsByClassName('like')[0];
	var unlike = post.getElementsByClassName('unlike')[0];
	var nb = post.getElementsByTagName('p')[0];

	nb.innerHTML = parseInt(nb.innerHTML) - 1;
	like.classList.toggle('hidden');
	unlike.classList.toggle('hidden');

}

// redirect user if he's not connected and try to like a photo

function redirect() {
	window.location.replace('gallery/actionLog')
}

function postComment() {

	xhr = new XMLHttpRequest();
	formData = new FormData();
	content = document.getElementsByTagName('textarea')[0].value;

	formData.append('photoId', postId);
	formData.append('comment', content);


	xhr.open('POST', 'gallery/addComment', true);

	xhr.onreadystatechange = function() {

		if (this.readyState == 4 && this.status == 200) {
			console.log('Request done!');
			var lastCom = JSON.parse(this.response.getElementById('dataBox').textContent);
			const elem = document.createElement('li');

			document.getElementsByTagName('textarea')[0].value = null;
			elem.innerHTML = "<h1>"+ lastCom.name + " <i>" + lastCom.comm_writeAt + "</i>" + "</h1>" + "<p>" + lastCom.comm_content + "</p><hr>";
			comment.insertBefore(elem, comment.firstChild);

		} else {
			console.log('ReadyState: ' + this.readyState + "\n" + 'Status :' + this.status);
		}

	}
	xhr.responseType= 'document'
	xhr.send(formData);
}