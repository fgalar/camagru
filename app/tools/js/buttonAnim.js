var button = document.querySelector('.menuToggle');
var nav = document.querySelector('.nav');

button.onclick = function()	{
	nav.classList.toggle('active');
	button.classList.toggle('active');
}
