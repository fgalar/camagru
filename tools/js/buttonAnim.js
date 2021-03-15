var button = document.querySelector('.menuToggle');
var nav = document.querySelector('.nav');

button.onclick = function()	{
	nav.classList.toggle('active'); // toggle modifie ajoute et ferme la classe a chque clic
	button.classList.toggle('active');
}
