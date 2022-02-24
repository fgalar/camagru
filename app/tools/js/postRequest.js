// PHP Exchanges
function postPHP(url, obj, callback = null) {
    xhr = new XMLHttpRequest();
    formData = new FormData();

	for (var key in obj) {
        formData.append(key, obj[key])
	}
    xhr.open('POST', url, true);
    xhr.onload = function() {
        if (xhr.status==200) {
            if (callback){
                callback(JSON.parse(this.response));
            }
        } else {
            console.log('ReadyState: ' + this.readyState + "\n" + 'Status :' + this.status);
        }
    }
    xhr.send(formData);
}