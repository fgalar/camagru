<?php

/**
 * Class Model est la classe constructrice
 * @var $connexion : tableau static(commun à toutes les instances) concervant pdo.
 * @var $table : table sql en cours de traitement
 */
class Model {

	protected $_pdo = null;
	protected $table = null;

	public function __construct() {
		$this->table = strtolower(get_class($this)) . 's';
		if ($this->_pdo === null){
			try {
				require './config/database.php';
				$this->_pdo = new pdo($DB_DSN, $DB_USER, $DB_PASSWORD);
				$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->_pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
				$this->_pdo->exec("USE $PROJECT");
			} catch(PDOException $e) {
				die($e->getMessage());
			}
		}
	}

	public function query($query, $params = [], $type = 's') {
		if ($params) {

			$stmt = $this->_pdo->prepare($query);

			if ($type == 'i') {
				foreach ($params as $key => $value) {
					$stmt->bindValue("$key", $value, PDO::PARAM_INT);
				}
				$stmt->execute();
				return $stmt;
			}

			$stmt->execute($params);
		} else {
			$stmt = $this->_pdo->query($query);
		}
		return $stmt;
	}

	public function lastInsertId() {
			return $this->_pdo->lastInsertId();
	}

	/**
	 * @param req tab|null ['to_set', 'params']
	 * @return tab
	 */
	public function find($req = []) {
		$sql = "SELECT * FROM Camagru. $this->table ";
		if (isset($req['conditions'])) {
			$sql .= ' WHERE ';
			$sql .= $this->put_and($req['conditions'], 'AND');
		}
		if (isset($req['params'])) {
			$stmt = $this->query($sql, $req['params']);
		} else {
			$stmt = $this->query($sql);
		}
		return $stmt;
	}

	public function set($req) {
		$sql = "INSERT INTO Camagru. $this->table SET ";
		if (isset($req['to_set'])) {
			$sql .= $this->put_and($req['to_set'], ',');
		}
		debug($req);
		if (isset($req['params'])) {
			$this->query($sql, $req['params']);
		} else { $this->query($sql); }
	}

	public function put_and($tab, $delim) {
		$sql = null;
		if (!is_array($tab)) {
			$sql .= $tab;
		} else {
			$cond = [];
			foreach ($tab as $key=>$value) {
				$cond[$key] = "$value";
			}
			$sql .= implode(" $delim ", $cond);
		}
		return $sql;
	}

	/**
	 * @param tab['to_update', 'conditions', 'params']
	 * @return void
	 */
	public function update($req) {
		$sql = "UPDATE Camagru. $this->table SET ";
		if (isset($req['to_update'])) {
			$sql .= $this->put_and($req['to_update'], ',');
		}
		if (isset($req['conditions'])) {
			$sql .= ' WHERE ';
			$sql .= $this->put_and($req['conditions'], 'AND');
		}
		if (isset($req['params'])) {
			$this->query($sql, $req['params']);
		} else {
			$this->query($sql);
		}
	}
}