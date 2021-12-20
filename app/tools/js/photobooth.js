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
	const preview = canvas.toDataURL('image/png');
	const link = document.createElement('a');

	link.href = preview;
	link.setAttribute('download', 'handsome');
	link.textContent = 'Download Image';
	link.innerHTML = `<img src="${preview}" class="scrolling" alt="Selfie" />`;
	capture.insertBefore(link, capture.firstChild);

	reloadImg();

	const data = canvas.toDataURL('image/png');
	postPhoto(filter.src, data);
	postPHP('photobooth/share', {selfie: data , filter: filter.src});
	if (method == 'photo') {
		selectFilter(filter.id);
	}

}

// Send datas photo to php server with post method
function postPhoto(choosenFilter, data) {
	xhr = new XMLHttpRequest();
	formData = new FormData();

	formData.append('selfie', data);
	formData.append('filter', choosenFilter);

	xhr.open('POST', 'photobooth/share', true);

	xhr.send(formData);
}


getVideo();
if (!video.addEventListener('canplay', videoStream)) {

	canvas.setAttribute("style", "display: none;");

}
if (typeof(uploader) !== 'undefined') {

	uploader.addEventListener('change', photoStream);

}




























// function paintToCanvas() {
// 	const width = video.videoWidth;
// 	const height = video.videoHeight;
// 	canvas.width = width;
// 	canvas.height = height;

// 	return setInterval(() => {
// 		ctx.drawImage(video, 0, 0, width, height);
// 		//take de pixels out
// 		let pixels = ctx.getImageData(0, 0, width, height);
// 		//mess with them
// 		//pixels = redEffect(pixels);
// 		pixels = rgbSplit(pixels);
// 		//ctx.globalAlpha = 0.1;
// 		// put them back
// 		ctx.putImageData(pixels, 0, 0);
// 	}, 16);
// }

// function takePhoto() {
// 	// played the sound
// 	snap.currentTime = 0;
// 	snap.play();
// 	// take the data out of the canvas
// 	const data = canvas.toDataURL('image/jpeg');
// 	const link = document.createElement('a');
// 	link.href = data;
// 	link.setAttribute('download', 'handsome');
// 	link.innerHTML = `<img src="${data}" alt="Picture" />`;
// 	strip.insertBefore(link, strip.firstChild);
// }

// function redEffect(pixels) {
// 	var red = getColor("red");
// 	var green = getColor("green");
// 	var blue = getColor("blue");
// 	for (let i = 0; i < pixels.data.length; i += 4) {
// 		pixels.data[i + 0] = pixels.data[i + 0] - red; //red -> cyan
// 		pixels.data[i + 1] = pixels.data[i + 1] - green;  //green -> rose
// 		pixels.data[i + 2] = pixels.data[i + 2] - blue; //blue -> jaune
// 	}
// 	return pixels;
// }

// function rgbSplit(pixels) {
// 	var split = getColor("alpha");
// 	for (let i = 0; i < pixels.data.length; i += 4) {
// 		pixels.data[i - split] = pixels.data[i + 0]; //red
// 		pixels.data[i + 100] = pixels.data[i + 1]; //green
// 		pixels.data[i - split] = pixels.data[i + 2]; //blue
// 	}
// 	return pixels;
// }

// function getColor(color) {
// 	slider = document.getElementById(color);
// 	return slider.value;
// }

