# PHP-Commands
Add sidekicks that perform php commands.

## Configuration
### Simple Command
Use for commands that need to be run every time you upgrade your service. Example: laravels `artisan migrate`
```json
{
	"php-commands":{
		"migrate":"/var/www/app/artisan migrate && /var/www/app/artisan db:seed"
	}
}
```
### Object Command
Use for commands that need to be run periodically
```json
{
	"php-commands":{
		"migrate":{
			"command":"/var/www/app/artisan migrate && /var/www/app/artisan db:seed",
			"schedule":{
				"second":0,
				"hour":18,
				"minute":0
			}
		}
	}
}
```
