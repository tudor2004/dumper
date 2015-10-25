<?php

namespace Tudorica\Dumper\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
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
		$this->comment('Loading database...');

		$this->database = $this->databaseController->getDatabase();

		$this->comment('Loading dump destination path...');

		Storage::disk('local')->makeDirectory(config('dumper.local_path'));

		$this->filename = date('YmdHis') . '.' . $this->database->getExtension();
		$this->filepath = preg_replace('/(\/+)/', '/', '/' . config('dumper.local_path') . '/' . $this->filename);

		$this->comment('Dumping data...');

		$success = $this->database->dump(storage_path('app') . $this->filepath);

		if($success === true)
		{
			$this->comment('Uploading to cloud...');

			Storage::disk('dropbox')->write($this->filename, Storage::disk('local')->get($this->filepath));

			$this->comment('Deleting temporary backup...');

			Storage::disk('local')->delete($this->filepath);

			$this->info('All done!');
		}
		else
		{
			$this->error(sprintf("\n" . 'Database backup failed. %s' . "\n", $success));
		}
	}
}
