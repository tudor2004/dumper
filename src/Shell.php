<?php

namespace Tudorica\Dumper;

use Symfony\Component\Process\Process;

class Shell
{
	public function execute($command)
	{
		$process = new Process($command);

		$process->run();

		if (!$process->isSuccessful())
		{
			return $process->getErrorOutput();
		}

		return true;
	}
}