<?php
use \Symfony\Component\Console\Application;
use \Symfony\Component\Console\Tester\CommandTester;

class DumpCommandTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();
	}

	public function tearDown()
	{
	}

	public function testDumpSuccess()
	{
		$command = Artisan::call('dumper:dump');
	}
}