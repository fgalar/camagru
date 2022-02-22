<?php

	abstract class Model
	{
		private $_pdo;

		public function __construct()
		{
			$this->set_pdo();
		}
	# PDO
		public function set_pdo()
		{
			$options = [
				PDO::ATTR_ERRMODE				=>	PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE	=>	PDO::FETCH_OBJ
			];
			try {
				require './config/database.php';
				$this->_pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $options);
				$this->_pdo->exec("USE $PROJECT");
			} catch(PDOException $e) {
				die('Error : ' . $e->getMessage());
			}
		}

		public function get_pdo()
		{
			return $this->_pdo;
		}
	# INSERT, SELECT, UPDATE, UPDATE
		public function insert($params)
		{
			# $params : [$field=>$value] to insert
			$_table = strtolower(get_class($this)) . 's';

			$table_columns = "(";
			$table_bindings = "(";
			foreach (array_keys($params) as $key)
			{
				$table_columns .= $key.", ";
				$table_bindings .= ":".$key.", ";
			}
			$table_columns = substr($table_columns, 0, -2).")";
			$table_bindings = substr($table_bindings, 0, -2).")";
			try
			{

				$stmt = $this->_pdo->prepare("INSERT INTO $_table $table_columns VALUES $table_bindings");
				$stmt->execute($params);
				return $this->select_one($params, '*');
			} catch (PDOException $e) {
				echo $e;
				$this->_pdo->rollback();
			}
		}

		public function select_one($params = [], $field = 'id')
		{
			# $params : $field => $value to find
			# field : model to return
			$_table = strtolower(get_class($this)) . 's';
			$cond = [];
			$req = "SELECT $field FROM $_table";

			if (!empty($params))
			{
				foreach (array_keys($params) as $column)
					$cond[] = " $column = :$column ";
				$req .= ' WHERE' . implode('AND', $cond);
			}

			try {
				$stmt = $this->_pdo->prepare($req);
				$stmt->execute($params);
				return $stmt->fetch();
			} catch (PDOException $e){
				echo $e;
				$this->_pdo->rollback();
			}
		}

		public function select_all($params = [], $field = '*')
		{
			# $params : $field => $value to find
			# field : model to return
			$_table = strtolower(get_class($this)) . 's';
			$cond = [];
			$req = "SELECT $field FROM $_table";

			if (!empty($params))
			{
				foreach (array_keys($params) as $column)
					$cond[] = " $column = :$column ";
				$req .= ' WHERE' . implode('AND', $cond);
			}
			$req .= "ORDER BY id DESC";

			try {
				$stmt = $this->_pdo->prepare($req);
				$stmt->execute($params);
				return $stmt->fetchall();
			} catch (PDOException $e){
				echo $e;
				$this->_pdo->rollback();
			}
		}

		public function update($params, $conditions = [])
		{
			# params : [$field=>value] to set in.
			# $condition : WHERE => [$field=>value]
			$table = strtolower(get_class($this)) . 's';

			$set_columns = [];
			foreach (array_keys($params) as $key)
				$set_columns[] = " $key = :$key";
			$req = "UPDATE $table SET" . implode(', ', $set_columns);

			if (!empty($conditions))
			{
				$condition = [];
				foreach ($conditions as $key => $value)
					$condition[] .= " $key = '$value'";
				$req .= " WHERE" . implode('AND ', $condition);
			}

			try {
				$stmt = $this->_pdo->prepare($req);
				$stmt->execute($params);
				return $this->select_one($conditions, '*');
			} catch (PDOException $e) {
				echo $e;
				$this->_pdo->rollBack();
			}
		}

		public function count_element($conditions = [])
		{
			$table = strtolower(get_class($this)) . 's';
			$req = "SELECT COUNT(*) AS count FROM $table";
			if (!empty($conditions))
			{
				$condition = [];
				foreach ($conditions as $key => $value)
					$condition[] .= " $key = '$value'";
				$req .= " WHERE" . implode('AND ', $condition);
			}
			try {
				$stmt = $this->_pdo->query($req);
				$stmt = $stmt->fetch();
				return $stmt->count;
			} catch (PDOException $e) {
				echo $e;
				$this->_pdo->rollBack();
			}
		}

		public function delete($conditions)
		{
			# $condition : WHERE => [$field=>value]
			$table = strtolower(get_class($this)) . 's';
			$req = "DELETE FROM $table";

			if (!empty($conditions))
			{
				$condition = [];
				foreach ($conditions as $key => $value)
				{
					$condition[] .= " $key = '$value'";
				}
				$req .= " WHERE" . implode(' AND ', $condition);
			}

			try {
				$stmt = $this->_pdo->query($req);
				return $stmt->fetch(PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				echo $e;
				$this->_pdo->rollBack();
			}
		}
	}
?>
