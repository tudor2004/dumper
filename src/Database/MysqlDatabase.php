<?php namespace Tudorica\Dumper\Database;

use Tudorica\Dumper\Shell;

/**
 * Class MysqlDatabase
 *
 * @package Tudorica\Dumper\Database\MysqlDatabase
 */
class MysqlDatabase
{
	protected $shell;
	protected $database;
	protected $username;
	protected $password;
	protected $host;
	protected $port;

	/**
	 * @param Shell $shell
	 * @param string $database
	 * @param string $username
	 * @param string $password
	 * @param string $host
	 * @param integer $port
	 */
	public function __construct(Shell $shell, $database, $username, $password, $host, $port)
	{
		$this->database = $database;
		$this->username = $username;
		$this->password = $password;
		$this->host     = $host;
		$this->port     = $port;
		$this->shell    = $shell;
	}

	/**
	 * @param string $destination
	 * @return mixed
	 */
	public function dump($destination)
	{
		$command = sprintf('mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s', escapeshellarg($this->username), escapeshellarg($this->password), escapeshellarg($this->host), escapeshellarg($this->port), escapeshellarg($this->database), escapeshellarg($destination));

		return $this->shell->execute($command);
	}

	/**
	 * @return string
	 */
	public function getExtension()
	{
		return 'sql';
	}
}