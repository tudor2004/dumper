<?php

namespace Tudorica\Dumper;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Tudorica\Dumper\Console\Commands\DumpCommand;
use Google_Client;
use Google_Service_Drive;

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


		Storage::extend('googledrive', function($app, $config) {

			$client = new Google_Client();
			$client->setClientId('594736589538-ldagttqbo5lhe6ucbbf78mo84r5t78pm.apps.googleusercontent.com');
			$client->setClientSecret('vWq8f6TmVun1jaOtx9ZnMq1E');
			$client->setAccessToken('{
				  "access_token":"xxxx",
				  "expires_in":3920,
				  "token_type":"Bearer",
				  "created":'.time().'
				}');

			$service = new Google_Service_Drive($client);

			return new Filesystem(new GoogleDriveAdapter($service));
		});

	}
}