<?php

namespace Tivoh\Torm;

abstract class Model {
	public function __construct(array $data = null) {
		if ($data != null) {
			$this->fromArray($data);
		}
	}

	public function save() {
		$insertFields = [];
		$insertPlaceholders = [];
		$updateFields = [];

		foreach (static::$fields as $field => $data) {
			$insertFields[] = Database::sanitize($field);
			$insertPlaceholders[] = ':' . $field;
			$updateFields[] = Database::sanitize($field) . '=' . ':' . $field;
		}

		$insertFields = implode(', ', $insertFields);
		$insertPlaceholders = implode(', ', $insertPlaceholders);
		$updateFields = implode(', ', $updateFields);

		$db = Database::getHandle();
		$st = $db->prepare('INSERT INTO ' . static::table . ' (' . $insertFields . ') VALUES (' . $insertPlaceholders . ') ON DUPLICATE KEY UPDATE ' . $updateFields);

		foreach (static::$fields as $field => $data) {
			$st->bindValue(':' . $field, $this->{$data[0]}, $data[1]);
		}

		return $st->execute();
	}

	public function delete() {
		$db = Database::getHandle();
		$st = $db->prepare('DELETE FROM ' . static::table . ' WHERE ' . Database::sanitize(static::primaryKey) . '=?');
		return $st->execute([$this->{static::$fields[static::primaryKey][0]}]);
	}

	protected function fromArray(array $data) {
		foreach ($data as $datum => $value) {
			if (array_key_exists($datum, static::$fields) && property_exists($this, static::$fields[$datum][0])) {
				$this->{static::$fields[$datum][0]} = $value;
			}
		}
	}

	public static function normalizeKey($key) {
		return preg_replace_callback('/_([a-z])/', function($matches) {
			return strtoupper($matches[1]);
		}, $key);
	}

	public static function find($params, array $options = array()) {
		$query = Query::get(static::table);

		foreach ($params as $key => $val) {
			$query->where($key, $val);
		}

		if (array_key_exists('order', $options)) {
			$query->order($options['order']);
		}

		if (array_key_exists('limit', $options) && is_numeric($options['limit']) && $options['limit'] >= 1) {
			$rows = $query->some($options['limit']);
		}
		else {
			$rows = $query->all();
		}

		if (count($rows) > 0) {
			$out = [];
			
			foreach ($rows as $row) {
				$out[]= new static($row);
			}

			return $out;
		}

		return null;
	}
}
