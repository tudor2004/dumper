<?php
namespace Tudorica\Dumper;

use Tudorica\Dumper\Database\MysqlDatabase;
use Tudorica\Dumper\Shell;

class DatabaseController
{
	/**
	 * @var Shell
	 */
	protected $shell;

	/**
	 * @var MysqlDatabase
	 */
	protected $database;

	/**
	 * @var string
	 */
	protected $destination;

	public function __construct()
	{
		$this->shell = new Shell();
	}

	public function getDatabase()
	{
		switch(config('database.default', 'mysql'))
		{
			case 'mysql':
			default:

				$this->database = new MysqlDatabase(
					$this->shell,
					config('database.connections.mysql.database'),
					config('database.connections.mysql.username'),
					config('database.connections.mysql.password'),
					config('database.connections.mysql.host'),
					config('database.connections.mysql.port', 3306)
				);
				break;
		}

		return $this->database;
	}
}