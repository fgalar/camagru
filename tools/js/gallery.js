// Displaying date when mouse hover
function display(id) {
	var figcaption = document.getElementById(id.toString());
	figcaption.classList.toggle('active');
}

// Open modal when you click on photo

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


span.onclick = function() {
	modal.style.display = "none";
}
