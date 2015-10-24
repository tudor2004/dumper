<?php

namespace Tudorica\Dumper\Console\Commands;

use Illuminate\Console\Command;
use Tudorica\Dumper\Database\MysqlDatabase;
use Tudorica\Dumper\DatabaseController;

class DumpCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'dumper:dump';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Dump database data.';

	/**
	 * @var DatabaseController
	 */
	protected $databaseController;

	/**
	 * @var MysqlDatabase
	 */
	protected $database;

	/**
	 * @var string
	 */
	protected $filename;

	/**
	 * @var string
	 */
	protected $filepath;

	public function __construct(DatabaseController $databaseController)
	{
		parent::__construct();

		$this->databaseController = $databaseController;
	}

	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle()
	{
		$this->comment('Loading dump destination path...');
		$this->checkDumpDestination();
		$this->filename = date('YmdHis') . '.' . $this->database->getExtension();
		$this->filepath = preg_replace('/(\/+)/', '/', config('dumper.local_path') . '/' . $this->filename);

		$this->comment('Loading database...');
		$this->loadDatabase();

		$this->comment('Dumping data...');

		$success = $this->database->dump($this->filepath);

		if($success === true)
		{
			$this->comment('Uploading to cloud...');
			// copy to google

			$this->comment('Deleting temporary backup...');
			// delete folder

			$this->info('All done!');
		}
		else
		{
			$this->error(sprintf("\n" . 'Database backup failed. %s' . "\n", $success));
		}
	}

	private function checkDumpDestination()
	{
		if(!is_dir(config('dumper.local_path')))
		{
			mkdir(config('dumper.local_path'));
		}
	}

	private function loadDatabase()
	{
		$this->database = $this->databaseController->getDatabase();
	}

}
