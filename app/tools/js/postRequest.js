// PHP Exchanges

function serialize_params(obj) {
    var params = [];
    for (var key in obj)
        if (obj.hasOwnProperty(key)) {
            params.push(key+ "=" + obj[key]) ;
        }
    return params.join("&");
}

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
                // console.log(this.response);
                callback(JSON.parse(this.response));
            }
            console.log('Request done!');
        } else {
            console.log('ReadyState: ' + this.readyState + "\n" + 'Status :' + this.status);
        }
    }
    xhr.send(formData);
}