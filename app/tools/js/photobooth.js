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
//	0 - Disabled snapshot button and unset upload.value onreload (for firefox persistance cache attribute)
snap["snapBtn"].setAttribute('disabled', "");
uploader.value = "";

function getVideo() {
	// Get access to camera!
	navigator.mediaDevices.getUserMedia({
		audio: false,
		video: {
			width: 640,
			height: 480,
		}}).then(function(stream) {
		video.srcObject = stream;
		video.play();
	}).catch(err => {
		console.log(err.name + ": " + err.message);
	})
}

function onPreview(stream) {
	/** Displayer Tools **/

	method = stream;

	// Hide all upload tools
	if (method == 'video') {
		display_upload = document.getElementById('uploadPhoto');
		display_upload.setAttribute('style', "display: none;");
	}

}

function unsetFilter() {
	if (prev_filter = document.querySelector('.selected-item'))
		prev_filter.classList.toggle('selected-item');
	filter = undefined;
}

function selectFilter(filterName) {

	if (userImg || method == 'video')
		snap["snapBtn"].removeAttribute('disabled');

	// remove selected filter color
	unsetFilter();
	// set colored selected filter
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
	if (this.files && this.files[0].size > 2097152) {

		alert("File is too big! Choose another one.");
		snap["snapBtn"].setAttribute('disabled', "");
		uploader.value = "";

	} else if (this.files && this.files[0] || typeof(userImg) != 'undefined') {

		if (this.files)
			userImg = window.URL.createObjectURL(this.files[0]);

		img = new Image();
		img.src = userImg;

		img.onload = function() {
			canvas.width = 640;
			canvas.height = 480;
			context.fillStyle = 'black';
			context.fillRect(0, 0, canvas.width, canvas.height)

			var ratio_cvs_img = get_ratio(canvas, img);
			var img_lenghts = dimension_adapter(canvas, img, ratio_cvs_img);

			if (typeof(filter) !== 'undefined') {
				var ratio_img_flt = get_ratio(img_lenghts, filter);
				if (img_lenghts.height >= canvas.height) {
					put_filter_in_bottom_center(canvas, filter, ratio_img_flt);
				} else {
					put_filter_in_middle_center(canvas, filter, ratio_img_flt, img_lenghts);
				}
			}
		}
	}

}

function get_ratio(dst, src) {
	var ratioX = dst.width / src.width;
	var ratioY = dst.height / src.height;
	return Math.min(ratioX, ratioY)
}

function put_filter_in_middle_center(dst, src, ratio, lenght_src) {
	context.drawImage(src,
		(dst.width / 2) - ((src.width * ratio) / 2),	//dst_x : center align
		(dst.height/2) - ((lenght_src.height) / 2),
		src.width * ratio, src.height * ratio
	)
	return { width: src.width * ratio,
		height: src.height * ratio};
}

function put_filter_in_bottom_center(dst, src, ratio) {
	context.drawImage(src,
		(dst.width / 2) - ((src.width * ratio) / 2),
		dst.height - src.height * ratio,
		src.width * ratio, src.height * ratio);
	return { width: src.width * ratio,
			 height: src.height * ratio};
}

function dimension_adapter(dst, src, ratio) {


	context.drawImage(src,
		(dst.width / 2) - ((src.width * ratio) / 2),	// dest_x : center of dst
		 dst.height/2 - (src.height * ratio) /2,		// dest_y : center of dst
		 src.width * ratio, src.height * ratio			// dest_width, dest_height
	);


	return { width: src.width * ratio,
			 height: src.height * ratio};
}

function reloadImg() {

	if (method == 'video'){
		context.drawImage(video, 0, 0, canvas.width, canvas.height);
	} else if (method == 'photo') {
		ratio = get_ratio(canvas, img);
		canvas = document.createElement('canvas');
		canvas.width = img.width * ratio;
		canvas.height = img.height * ratio;
		ctx = canvas.getContext('2d');
		ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
	}
}

// Take a snapshot
function takePhoto(){

	snap['snapNoise'].play();

	const flt_url = filter.src
	// remove filter from image
	reloadImg();
	const img_url = canvas.toDataURL('image/png');

	postPHP('photobooth/share', {'filter': flt_url,'selfie' :img_url},
			   function(new_photo) {
		// Add div > span + img;

		canvas = document.getElementById('imgPreview');
		photo_element = document.createElement('div');
		span = document.createElement('span');
		img = document.createElement('img');

		photo_element.setAttribute('id', new_photo.path);
		photo_element.setAttribute('class', "save_photo");

		span.setAttribute('class', 'clickable deleteCross');
		span.setAttribute('onclick', "delete_picture('"+new_photo.path+"')");
		span.innerHTML = "×";
		photo_element.appendChild(span);

		img.src = new_photo.path;
		img.setAttribute('class', 'side_img');
		photo_element.appendChild(img);

		capture.insertBefore(photo_element, capture.firstChild);
	});

}

function delete_picture(path) {

	postPHP('photobooth/delete_post', {'picture': path});
	var post = document.getElementById(path);
	post.classList.toggle('hidden');

}

//---- MAIN -----//

//	1 - Ask for webcam
getVideo();
//	2 - Accept (1) || Refuse (2) webcam
video.oncanplay = videoStream;	// (1)
uploader.oninput = photoStream;	// (2)
//	3 - Snapshot
snap['snapBtn'].onclick = takePhoto;

