# PHP-Commands
Add services or sidekicks that perform php commands.
Config versions up to 3 will default to sidekicks, while versions
3 or above default to services.

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
			"is-service":false,
			"keepalive":false,
			"schedule":{
				"second":0,
				"hour":18,
				"minute":0
			}
		}
	}
}
```

- `share-network` share the network of the main container. Only available when running as sidekick
- `keepalive` adds a dummy container which does nothing but stays active. The commands then join the network of this active container.  
  This helps with problems with the rancher dns service taking a few seconds after the container start to work. It also
  improves reliability of sidekick services joining the network of your container because they don't have to restart with your original command.
