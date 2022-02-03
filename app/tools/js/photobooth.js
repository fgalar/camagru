var video = document.getElementById('video');
var canvas = document.getElementById('imgPreview');
var context = canvas.getContext('2d');
var snap = document.getElementsByClassName('snap');
var capture = document.getElementById('side');

var filter = undefined;
var method = undefined;
var userImg = undefined;
var uploader = document.getElementById('uploader');
var img = undefined;
// Get access to camera!
function getVideo() {
	navigator.mediaDevices.getUserMedia({
		audio: false,
		video: {
			width: 640,
			height: 480,
		}}).then(function(stream) {
		video.srcObject = stream;
		video.play();
		console.log(stream);
	}).catch(err => {
		console.log(err.name + ": " + err.message);
	})
}

/** Displayer Tools **/
function onPreview(stream) {

	method = stream;

	if (method == 'video') {
		uploaderDiv = document.getElementById('uploadPhoto');
		uploaderDiv.setAttribute('style', "display: none;");
	}
	canvas.setAttribute('style', "display: flex;");

}

function selectFilter(filterName) {

	snap["snapBtn"].removeAttribute('disabled');

	if (prev_filter = document.querySelector('.selected-item'))
		prev_filter.classList.toggle('selected-item');

	filter = document.getElementById(filterName);
	filter.classList.toggle('selected-item');
	if (method == 'photo') {
		photoStream();
	}

}

function videoStream() {

	onPreview('video');

	const width = video.videoWidth;
	const height = video.videoHeight;
	canvas.width = width;
	canvas.height = height;

	return setInterval(() => {
		context.drawImage(video, 0, 0, width, height);
		if (typeof(filter) != 'undefined') {
			context.drawImage(filter, 0, 0, width, height);
		}
	} , 16);

}

function photoStream() {

	onPreview('photo');

	if (this.files && this.files[0] || typeof(userImg) != 'undefined') {

		if (this.files)
			userImg = window.URL.createObjectURL(this.files[0]);


		img = new Image();
		img.src = userImg;

		img.onload = function() {

			canvas.width = img.width;
			canvas.height = img.height;
			context.drawImage(img, 0, 0, img.width, img.height);

			if (typeof(filter) !== 'undefined') {

				context.drawImage(filter, (canvas.width - 640), (canvas.height - 480));

			}
		}
	}

}

function reloadImg() {

	if (method == 'video'){
		context.drawImage(video, 0, 0, canvas.width, canvas.height);
	}
	if (method == 'photo') {
		context.drawImage(img, 0, 0, canvas.width, canvas.height);
	}

}

// Take a snapshot
function takePhoto(){

	snap['snapNoise'].play();
	// remove filter from image
	reloadImg();

	const data = canvas.toDataURL('image/png');

	postPhoto(filter.src, data, function(new_photo) {
		// Add div > span + img;
		photo_element = document.createElement('div');
		span = document.createElement('span');
		img = document.createElement('img');

		photo_element.setAttribute('id', new_photo.path);
		photo_element.setAttribute('class', "save_photo");

		// TODO: deleteCross must be a class and not an id because is not unique.
		span.setAttribute('id', "deleteCross");
		span.setAttribute('class', 'clickable');
		span.setAttribute('onclick', "delete_picture('"+new_photo.path+"')");
		span.innerHTML = "X";
		photo_element.appendChild(span);

		img.src = new_photo.path;
		img.setAttribute('class', 'side_img');
		photo_element.appendChild(img);

		capture.insertBefore(photo_element, capture.firstChild);
	});

	if (method == 'photo') {
		selectFilter(filter.id);
	}

}

// Send datas photo to php server with post method
function postPhoto(choosenFilter, data, callback=null) {
	// TODO: #doublon Voir pour formData all post requests

	xhr = new XMLHttpRequest();
	formData = new FormData();

	formData.append('selfie', data);
	formData.append('filter', choosenFilter);

	xhr.open('POST', 'photobooth/share', true);

	xhr.onload = function() {
        if (xhr.status==200) {
            if (callback){
                callback(JSON.parse(this.response));
            }
			console.log('Request done!');

        } else {
            console.log('ReadyState: ' + this.readyState + "\n" + 'Status :' + this.status);
        }
    }

	xhr.send(formData);
}


getVideo();
if (!video.addEventListener('canplay', videoStream)) {

	canvas.setAttribute("style", "display: none;");

}
if (typeof(uploader) !== 'undefined') {

	uploader.addEventListener('change', photoStream);

}

function delete_picture(path) {
	console.log(path)

	// TODO : Fonction doublon gallery et photobooth
	// if (confirm('You’re about to erase that image, are you sure')){

		postPHP('gallery/delete_post', {picture: path});
		var post = document.getElementById(path);
		post.classList.toggle('hidden');

	// }
}
