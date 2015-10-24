<?php

class DatabaseControllerTest extends TestCase
{
	public function testGetDatabase()
	{
		$databaseController = new \Tudorica\Dumper\DatabaseController();

		$database = $databaseController->getDatabase();

		$this->assertInstanceOf('Tudorica\Dumper\Database\MysqlDatabase\MysqlDatabase', $database);
	}
}