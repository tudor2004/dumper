<?php

use Tudorica\Dumper\Shell;

class ShellTest extends TestCase
{
	/**
	 * @var Shell
	 */
	protected $shell;

	public function setUp()
	{
		$this->shell = new Shell();
	}

	public function testSuccess()
	{
		$this->assertTrue($this->shell->execute('true'));
	}

	public function testFailure()
	{
		$this->assertTrue($this->shell->execute('false') !== true);
	}
}