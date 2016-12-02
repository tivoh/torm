# torm
A small ORM framework for PHP.

The **torm** utility (`vendor/bin/torm`) expects a table schema in a .toml file in `config/models/`. 
This can be overwritten with the `--input` command-line argument.

The generated classes are put in to `models/`. This can be overwritten with the `--output` command-line argument.

## Example: user.toml => User.php

### user.toml

```toml
# The name of the generated class
class = "User"

# The name of the table in the database
table = "users"

# Defines a field
[[fields]]
	# The name of the column in the database table
	name = "id"

	# The type of the column, this can be one of ["primary", "string", "text", "int", "bool", "timestamp"]
	type = "primary"

[[fields]]
	name = "uuid"
	type = "string"
	
	# The size of the string (not used yet, but will later be used for generating the SQL schema)
	size = 255

	# The index type of the column (not used yet, but will later be used for generating the SQL schema)
	index = "unique"

[[fields]]
	name = "name"
	type = "string"
	size = 255
	index = "unique"

[[fields]]
	name = "password"
	type = "string"
	size = 255

[[fields]]
	name = "email"
	type = "string"
	size = 255
```

### This generates the following User.php

```php
<?php

class User extends Torm\Model {
	const table = 'users';
	const primaryKey = 'id';

	protected $id;

	protected $uuid;

	protected $name;

	protected $password;

	protected $email;

	protected static $fields = [
		'id' => ['id', \PDO::PARAM_INT],
		'uuid' => ['uuid', \PDO::PARAM_STR],
		'name' => ['name', \PDO::PARAM_STR],
		'password' => ['password', \PDO::PARAM_STR],
		'email' => ['email', \PDO::PARAM_STR]
	];

	public function getId() {
		return $this->id;
	}

	public function getUuid() {
		return $this->uuid;
	}

	public function getName() {
		return $this->name;
	}

	public function getPassword() {
		return $this->password;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setId($value) {
		$this->id = $value;
	}

	public function setUuid($value) {
		$this->uuid = $value;
	}

	public function setName($value) {
		$this->name = $value;
	}

	public function setPassword($value) {
		$this->password = $value;
	}

	public function setEmail($value) {
		$this->email = $value;
	}

	public static function findOneById($value) {
		$objects = static::find(['id' => $value], 1);

		if ($objects) {
			return $objects[0];
		}
	}

	public static function findById($value) {
		return static::find(['id' => $value]);
	}

	public static function findOneByUuid($value) {
		$objects = static::find(['uuid' => $value], 1);

		if ($objects) {
			return $objects[0];
		}
	}

	public static function findByUuid($value) {
		return static::find(['uuid' => $value]);
	}

	public static function findOneByName($value) {
		$objects = static::find(['name' => $value], 1);

		if ($objects) {
			return $objects[0];
		}
	}

	public static function findByName($value) {
		return static::find(['name' => $value]);
	}

	public static function findOneByPassword($value) {
		$objects = static::find(['password' => $value], 1);

		if ($objects) {
			return $objects[0];
		}
	}

	public static function findByPassword($value) {
		return static::find(['password' => $value]);
	}

	public static function findOneByEmail($value) {
		$objects = static::find(['email' => $value], 1);

		if ($objects) {
			return $objects[0];
		}
	}

	public static function findByEmail($value) {
		return static::find(['email' => $value]);
	}

}

```