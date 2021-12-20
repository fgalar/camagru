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
    xhr.open('POST', url, true);
    params = serialize_params(obj);

    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

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
    xhr.send(params);
}