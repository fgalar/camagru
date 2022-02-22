var button = document.querySelector('.menuToggle');
var nav = document.querySelector('.nav');

button.onclick = function()	{
	nav.classList.toggle('active');
	button.classList.toggle('active');
}


// Colorized the current menu element visited
window.onload = function ()	{
	var url = document.URL;
	var title_lst = nav.getElementsByTagName('a') ;

	for (var i = 0; i < title_lst.length; i++) {
		if (!title_lst[i].href.localeCompare(url))
			title_lst[i].classList.toggle('active');
	}

}