# PHP-Commands
Add sidekicks that perform php commands.

## Defaults
The default value for `php-commands` is artisan migrate and artisan seed.
The reason for this is backwords compabtility with rancherize versions where the nginx image would take care of this step.
To disable this behaviour simply add 

```json 
	"php-commands":[]
```
to your environments and/or defaults

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
