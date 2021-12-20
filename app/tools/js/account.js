var span		= document.querySelectorAll(".remove");

function request(data) {

	xhr = new XMLHttpRequest();

	xhr.open('POST', 'user/account/removeImg', true);
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


span.forEach(function(element) {
	element.addEventListener("click", function() {
		elem = element.parentElement;
		idImgToRm = elem.getElementsByTagName('img')[0];
		request(idImgToRm.alt);
		elem.remove();
	});
});