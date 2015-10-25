<?php

namespace Tudorica\Dumper;

use Dropbox\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Dropbox\DropboxAdapter;
use League\Flysystem\Filesystem;
use Tudorica\Dumper\Console\Commands\DumpCommand;

class DumperServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'dumper');

		$databaseController = new DatabaseController();

		$this->app->singleton('command.dumper.dump', function ($app) use ($databaseController)
		{
			return new DumpCommand($databaseController);
		});

		$this->commands('command.dumper.dump');
	}

	/**
	 * Bootstrap any application services.
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			                 __DIR__ . '/../config/config.php' => config_path('dumper.php'),
		                 ]);

		Storage::extend('dropbox', function ($app, $config)
		{
			$client = new Client($config['access_token'], $config['secret']);

			return new Filesystem(new DropboxAdapter($client));
		});

	}
}