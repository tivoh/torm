<?php

class <CLASS> extends Tivoh\Torm\Model {
	const table = '<TABLE>';
	const primaryKey = '<PRIMARY KEY>';

<FIELD>
	protected $<PROPERTY NAME>;
</FIELD>

	protected static $fields = [
		<FIELD NAMES>
	];

<GETTER>
	public function get<METHOD NAME>() {
		return $this-><PROPERTY NAME>;
	}
</GETTER>

<FOREIGN>
	public function get<METHOD NAME>() {
		if ($this-><PROPERTY NAME> == null) {
			$objects = <FOREIGN CLASS>::find(['<FOREIGN KEY>' => $this->get<BY METHOD NAME>()], 1);

			if ($objects) {
				$this-><PROPERTY NAME> = $objects[0];
			}
		}

		return $this-><PROPERTY NAME>;
	}
</FOREIGN>

<SETTER>
	public function set<METHOD NAME>($value) {
		$this-><PROPERTY NAME> = $value;
	}
</SETTER>

<FINDER>
	public static function findOneBy<METHOD NAME>($value) {
		$objects = static::find(['<FIELD NAME>' => $value], 1);

		if ($objects) {
			return $objects[0];
		}
	}

	public static function findBy<METHOD NAME>($value, array $options = array()) {
		return static::find(['<FIELD NAME>' => $value], $options);
	}
</FINDER>
}
