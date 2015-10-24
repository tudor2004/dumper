<?php

namespace Tudorica\Dumper;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Tudorica\Dumper\Console\Commands\DumpCommand;

class DumperServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'dumper');

		$databaseController = new DatabaseController();

		$this->app->singleton('command.dumper.dump', function($app) use ($databaseController) {
			return new DumpCommand($databaseController);
		});

		$this->app->singleton('google.api.client', function($app) {
			return new GoogleClient();
		});

		$this->commands('command.dumper.dump');
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			                 __DIR__ . '/../config/config.php' => config_path('dumper.php'),
		                 ]);

		/*
		Storage::extend('googledrive', function($app, $config) {
			$client = new GoogleDriveClient(
				$config['accessToken'], $config['clientIdentifier']
			);

			return new Filesystem(new DropboxAdapter($client));
		});
		*/
	}
}