// Displaying date when mouse hover

function display(id) {
	var figcaption = document.getElementById(id.toString());
	figcaption.classList.toggle('active');
}

// Open modal on photo click

var tableImg	= document.querySelectorAll(".photo");
var modal		= document.getElementById("myModal");
var modalImg	= document.getElementById('img01');
var captionText	= document.getElementById("caption");
var span		= document.getElementsByClassName("close")[0];

function focusImg(photo) {
	photo.onclick = function() {
		modal.style.display = "block";
		modalImg.src = photo.src;
		captionText.innerHTML = photo.alt;
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
	formData = new FormData();


	xhr.open('POST', action, true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {

		// Control states of resquest
		if (this.readyState == 4 && this.status == 200) {
			//console.log(this.response);
		} else {
			return -1;
		}

	}

	xhr.send('postId=' + data);
}

function like(id) {

	begForALike(id, 'gallery/like');

	var post = document.getElementById(id);
	var like = post.getElementsByClassName('like')[0];
	var unlike = post.getElementsByClassName('unlike')[0];
	var nb = post.getElementsByTagName('p')[0];

	nb.innerHTML = parseInt(nb.innerHTML) + 1;
	like.classList.toggle('hidden');
	unlike.classList.toggle('hidden');

}


function unlike(id) {

	begForALike(id, 'gallery/unlike');

	var post = document.getElementById(id);
	var like = post.getElementsByClassName('like')[0];
	var unlike = post.getElementsByClassName('unlike')[0];
	var nb = post.getElementsByTagName('p')[0];

	nb.innerHTML = parseInt(nb.innerHTML) - 1;
	like.classList.toggle('hidden');
	unlike.classList.toggle('hidden');

}

function redirect() {
	window.location.replace('gallery/actionLog')
}