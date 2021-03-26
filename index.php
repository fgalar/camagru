<?PHP
// Just uncomment line below if you want to Generate OR Regenerate the DB 
//require_once 'config/setup.php';


spl_autoload_register(function ($class) {
	if (file_exists('./core/' . $class . '.php')) {
		require_once './core/' . $class . '.php';
	} else if (file_exists('./controllers/' . $class . 'Controller.php')) {
		require_once './controllers/' . $class . 'Controller.php';
	} else if (file_exists('./models/' . $class . '.php')) {
		require_once './models/' . $class . '.php';
	}
});

new Dispatcher();
