<?php
require_once "config/setup.php";

spl_autoload_register(function ($class) {
    require_once './tools/functions.php';
    if (file_exists('./core/' . $class . '.php')) {
        require_once './core/' . $class . '.php';
    } else if (file_exists('./controllers/' . $class . '.php')) {
        require_once './controllers/' . $class . '.php';
    } else if (file_exists('./models/' . $class . '.php')) {
        require_once './models/' . $class . '.php';
    }
});

new Dispatcher();
