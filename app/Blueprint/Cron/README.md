# Support for the rancher cronjob service
Many website systems require periodic updates for example for their sitemaps. This internal plugin allows blueprints
to easily support adding cronjob sidekicks

## Use-case
(Re-)Create sitemap data every night.

## Options
- command: The command given to the entrypoint of your image
- schedule: When to run the command. This has a cron-like syntax meaning `*` stands for `any`. `*/XX` means every XX
  - String: `second minute hour month dayOfMonth dayOfWeek` e.g. `00 0 * * * *`: run every full hour.
  - Object: Object with the following attributes. Any attribute that is not given will default to `*`
    - hour
    - minute
    - second
    - month
    - dayOfMonth
    - dayOfWeek

## Examples
- Run the PHP Command `php /var/www/app/artisan sitemap:make` every day at 2:30 am for every environment
```json
{ 
	"default":{ 
		"cron":{
			"sitemap":{
				"command":"php /var/www/app/artisan sitemap:make",
				"schedule":{
					"hour": 2,
					"minute": 30,
					"second": 0
				}
			}
		}
	} 
}
```

- Run the PHP Command `php /var/www/app/artisan sitemap:make` every day at 2:30 am for the environment `staging`
```json
{ 
	"environments":{ 
		"staging":{
			"cron":{
				"sitemap":{
					"command":"php /var/www/app/artisan sitemap:make",
					"schedule":{
						"hour": 2,
						"minute": 30,
						"second": 0
					}
				}
			}
		} 
	} 
}
```

## Use in Blueprint
### Init
This simply creates a `"cron":[]` object in the configuration if it does not exist yet

```php
/**
 * @var CronInit $cronInitializer
 */
$cronInitializer = container('cron-init');
$cronInitializer->init( $fallbackConfigurable, $initializer );
```

### Build
The CronParser creates a service for every `cron` entry, sets the labels to start it by the given schedule and adds it
to the infrastructure. To create the service the Closure given as 3rd Parameter is called with the name and the command
that should be ran. It is expected to return the created service.  
This closure allows altering each service before it is added to the infrastructure.  
Recommended alterations to do here:
- Make it a sidekick of the main service
- `volumes_from` targeting the container containing your app
- Setting an image that will run commands expected from your blueprint (webserver -> php image)

```php
/**
 * @var CronParser $cronParser
 */
$cronParser = container('cron-parser');
$cronParser->parse($config, $infrastructure, function($name, $command) use ($phpFpmMaker, $serverService, $config) {
	return $phpFpmMaker->makeCommand($name, $command, $serverService, $config);
});
```