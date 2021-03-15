<?php

/**
 * Fichier config/setup.php: permet de créer ou recréer le schéma de la base de données,
 * en utilisant les infos contenues dans le fichier config/database.php.
 * /!\ A inclure dans index.php seulement si on veut reset la DB, ou la créer.
 **/

require_once 'config/database.php';

/** Connection à la base de données **/
try {
	$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	echo "Connected successfully to DB. ";
} catch(PDOException $e) {
	die("Connexion failed: " . $e->getMessage());
}
/** Création ou recréation de la DB **/
try {
	$pdo->exec("DROP DATABASE IF EXISTS $PROJECT");
	$pdo->exec("CREATE DATABASE $PROJECT");
	$pdo->exec("USE $PROJECT");
	echo "Create new DB $PROJECT with success";
} catch (PDOException $e) {
	die("Failed creating: " . $e->getMessage());
}

/**  Création ou recréation de la TABLE users **/
try {
	$pdo->exec("CREATE TABLE `accounts` (
		`account_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`account_name` VARCHAR(20) NOT NULL,
		`account_mail` VARCHAR(50) NOT NULL,
		`account_pass` VARCHAR(80) NOT NULL,
		`account_token` VARCHAR(60) NULL,
		`account_confirmedAt` DATE,
		`account_tokenResetPass` VARCHAR(60) NULL,
		`account_resetPassAt` DATE,
		`account_tokenRememberMe` VARCHAR(255) NULL
	)");
	echo "Create new users TABLE with success";
} catch(PDOException $e) {
	die("Failed creating accounts TABLE: " . $e->getMessage());
}

/**  Création ou recréation de la TABLE photos **/
try {
	$pdo->exec("CREATE TABLE `photos` (
		`photo_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`photo_path` VARCHAR(50) NOT NULL ,
		`photo_takeAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`photo_nbLikes` INT DEFAULT 0,
		`photo_nbComm` INT DEFAULT 0,
		`photo_userId` INT NOT NULL ,
		FOREIGN KEY (photo_userId) REFERENCES accounts(account_id)
		)");
	echo "Create new photos TABLE with success";
} catch(PDOException $e) {
	die("Failed creating photos TABLE: " . $e->getMessage());
}

/**  Création ou recréation de la TABLE comments **/
try {
	$pdo->exec("CREATE TABLE `comments` (
		`comm_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`comm_content` VARCHAR(255) NOT NULL ,
		`comm_writeAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
		`comm_by` INT NOT NULL ,
		`comm_for` INT NOT NULL,
		FOREIGN KEY (comm_by) REFERENCES accounts(account_id),
		FOREIGN KEY (comm_for) REFERENCES photos(photo_id)
		)");
	echo "Create new comments TABLE with success";
} catch(PDOException $e) {
	die("Failed creating comments TABLE: " . $e->getMessage());
}

/**  Création ou recréation de la TABLE likes **/
try {
	$pdo->exec("CREATE TABLE `likes` (
		`like_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`like_photoId` INT NOT NULL ,
		`like_userId` INT NOT NULL ,
		FOREIGN KEY (like_photoId) REFERENCES photos(photo_id),
		FOREIGN KEY (like_userId) REFERENCES accounts(account_id)
		)");
	echo "Create new likes TABLE with success";
} catch(PDOException $e) {
	die("Failed creating likes TABLE: " . $e->getMessage());
}

/** Création de notre premier utilisateur **/
try {
	$pass = password_hash('fgarault', PASSWORD_BCRYPT);
	$pdo->exec("INSERT INTO `accounts` (`Account_id`, `Account_name`, `Account_mail`, `Account_pass`, `Account_token`, `Account_confirmedAt`, `Account_tokenResetPass`, `Account_resetPassAt`, `Account_tokenRememberMe`)
	VALUES
	('1', 'fgarault', 'fgarault@gmail.com', '$pass', NULL, NOW(), NULL, NULL, NULL);
	");
	// echo "New Account fgarault created with success";
} catch (PDOException $e) {
	die("Failed insert our first Account 🤨 : " . $e->getMessage());
}

/** Chargement de fakephoto **/
require 'core/functions.php';
try {
	$dir = 'tmp';
	$scanned_directory = array_diff(scandir($dir, SCANDIR_SORT_DESCENDING), array('..', '.', '.DS_Store'));
	debug($scanned_directory);
	foreach ($scanned_directory as $file) {
		$pdo->exec("INSERT INTO `photos` (`photo_path`, `photo_takeAt`, `photo_userId`)
		VALUES
		('tmp/$file', CURRENT_TIMESTAMP, '1');
		");
	}

} catch (PDOException $e) {
	die("Failed insert our album 🤨 : " . $e->getMessage());
}