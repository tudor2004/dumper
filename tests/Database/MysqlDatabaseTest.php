<?php

use Mockery as m;

class MysqlDatabaseTest extends TestCase
{
	protected $shell;

	protected $database;

	public function setUp()
	{
		$this->shell = m::mock('Tudorica\Dumper\Shell');
		$this->database = new \Tudorica\Dumper\Database\MysqlDatabase($this->shell, 'testDatabase', 'testUser', 'password', 'localhost', '3306');
	}

	public function tearDown()
	{
		m::close();
	}

	public function testDumpSuccess()
	{
		$this->shell->shouldReceive('execute')
					->with("mysqldump --user='testUser' --password='password' --host='localhost' --port='3306' 'testDatabase' > 'testfile.sql'")
					->once()
					->andReturn(true);

		$this->assertTrue($this->database->dump('testfile.sql'));
	}
}