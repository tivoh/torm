<?php

namespace Torm;

class Query {
	protected $whereStr = '';
	protected $paramCount = 0;
	protected $params = array();
	protected $table;
	protected $primaryKey;
	protected $query;
	protected $limit;
	protected $offset;

	protected function __construct($query) {
		$this->query = $query;
	}

	public static function get($table) {
		return new static('SELECT * FROM ' . Database::sanitize($table));
	}

	public function where($key, $value) {
		if ($this->whereStr == '') {
			$this->whereStr .= ' WHERE ';
		}
		else {
			$this->whereStr .= ' AND ';
		}

		$this->whereStr .= Database::sanitize($key) . ' = :p' . $this->paramCount;
		$this->params[':p' . $this->paramCount] = $value;
		
		++$this->paramCount;

		return $this;
	}

	public function all($offset = -1, $returnType = \PDO::FETCH_ASSOC) {
		return $this->some(-1, $offset, $returnType);
	}

	public function some($limit, $offset = -1, $returnType = \PDO::FETCH_ASSOC) {
		$this->limit = $limit;
		$this->offset = $offset;

		$st = $this->run();

		if ($st !== false) {
			$rows = $st->fetchAll($returnType);

			if ($rows === false) {
				return [];
			}

			return $rows;
		}

		return [];
	}

	public function one($offset = -1, $returnType = \PDO::FETCH_ASSOC) {
		$this->limit = 1;
		$this->offset = $offset;

		$st = $this->run();

		if ($st !== false) {
			$row = $st->fetch($returnType);

			if ($row === false) {
				return null;
			}

			return $row;
		}

		return null;
	}

	public function run() {
		$query = $this->query;
		$query .= $this->whereStr;

		if (is_numeric($this->limit) && $this->limit > 0) {
			$query .= ' LIMIT ' . $this->limit;
		}

		if (is_numeric($this->offset) && $this->offset > 0) {
			$query .= ' OFFSET ' . $this->offset;
		}

		$db = Database::getHandle();

		$st = $db->prepare($query);

		if ($st->execute($this->params)) {
			return $st;
		}

		return false;
	}
}
