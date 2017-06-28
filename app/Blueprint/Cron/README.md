# Support for the rancher cronjob service
Many website systems require periodic updates for example for their sitemaps. This internal plugin allows blueprints
to easily support adding cronjob sidekicks

## Use-case

## Example
```json
{
	"environments":{
		"staging":{
			"cron":{
				"sitemap":{
					"command":"php /var/www/app/artisan make-sitemap",
					"schedule":{
						"hour": 2,
						"minute": 30
					}
				}
			}
		}
	}
}
```