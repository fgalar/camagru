<?PHP

/**
 * Class Model is the constructive class containing all general functions of request DB
 * @var $_pdo : Access to Database.
 * @var $table : Requested by $model (each $model refered to a SQL table).
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

	/**
	 *  @param $query string of request.
	 *  @param $params tab to send with request.
	 *  @param $type when set LIMIT or OFFSET/not convert to INT by mySQL.
	 *  @return $stmt result of the request.
	 **/
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
	 * Find row.s in a $table
	 * @param req tab['to_set', 'params']
	 * @return $stmt result of the request.
	 */
	public function find($req = []) {
		$sql = "SELECT * FROM Camagru. $this->table ";
		if (isset($req['conditions'])) {
			$sql .= ' WHERE ';
			$sql .= $this->add_SqlParam($req['conditions'], 'AND');
		}
		if (isset($req['params'])) {
			$stmt = $this->query($sql, $req['params']);
		} else {
			$stmt = $this->query($sql);
		}
		return $stmt;
	}

	/**
	 *  set a new line in mySQL DB
	 *  @param $req ['to_set', 'params']
	 **/
	public function set($req) {
		$sql = "INSERT INTO Camagru. $this->table SET ";
		if (isset($req['to_set'])) {
			$sql .= $this->add_SqlParam($req['to_set'], ',');
		}
		if (isset($req['params'])) {
			$this->query($sql, $req['params']);
		} else { $this->query($sql); }
	}

	/**
	 *  Permit addition of SQL conditions or param
	 *  @param $tab all arguments to add
	 *  @param $delimiter char ',' OR 'AND'..
	 **/
	private function add_SqlParam($tab, $delim) {
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
	 *  Update a SQL table's row
	 *  @param tab['to_update', 'conditions', 'params']
	 **/
	public function update($req) {
		$sql = "UPDATE Camagru. $this->table SET ";
		if (isset($req['to_update'])) {
			$sql .= $this->add_SqlParam($req['to_update'], ',');
		}
		if (isset($req['conditions'])) {
			$sql .= ' WHERE ';
			$sql .= $this->add_SqlParam($req['conditions'], 'AND');
		}
		if (isset($req['params'])) {
			$this->query($sql, $req['params']);
		} else {
			$this->query($sql);
		}
	}

	/**
	 *  Delete a row of a SQL table.
	 *  @param tab['conditions', 'params']
	 **/
	public function del($req) {
		$sql = "DELETE FROM Camagru. $this->table";

		if (isset($req['conditions'])) {
			$sql .= ' WHERE ';
			$sql .= $this->add_SqlParam($req['conditions'], 'AND');
		}
		if (isset($req['params'])) {
			$this->query($sql, $req['params']);
		} else {
			$this->query($sql);
		}
	}


}
