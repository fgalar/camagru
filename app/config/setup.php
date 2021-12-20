<?php
	require 'config/database.php';

	try {

			$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

			$sql = file_get_contents("config/backup.sql");
			$pdo->exec($sql);
			// echo "DB : ✅.<br/>";
		} catch(PDOException $e) {
			die("Connexion failed: " . $e->getMessage());
		}
