# Healthcheck
Set up a rancher healthcheck that periodically opens connections to the container to check whether the service running
in it is actually still doing its intended job.

## Options
All options lie in the `healthcheck` json object.
- `enable` can be set to `false` to disable the healthcheck without removing its data. Intended for debugging. defaults 
  to `true`
- `port` the port to query for a successful connection. Defaults to `80`
- `url` http url to check. This will enable http 1.0 checking instead of tcp 'conection opens' checking
- `strategy` define what rancher does when the healthcheck fails. Default `none` Possible values: `none`, `recreate`  
  The value for recreate if service is not yet known
- `interval`: Interval between healthchecks in ms. Defaults to `2000`
- `response-timeout`: Time in ms before a stalled request is counted as having failed. Defaults to `2000`
- `init-timeout`: Time before the first healthcheck is attempted in ms. Defaults to `60000`
- `reinit-timeout`: Time after a restart before the first healthcheck is attempted in ms. Defaults to `60000`
- `healthy-threshold`: Number of successful requests before the service counts as being healthy. Defaults to `2`
- `unhealthy-threshold`: Number of failed requests before the service counts as being unhealthy. Defaults to `3`

## Examples
- HTTP1.0 Healthcheck to `/` on port 80 for all environments
```json
{
	"default": {
		"healthcheck":{
			"url":"\/"
		}
	}
}
```

- TCP Healthcheck to port 80 for all environments
```json
{
	"default": {
		"healthcheck":{
			"url":"\/"
		}
	}
}
```

- HTTP1.0 Healthcheck to `/` on port 80 for single envionment `production`
```json
{
	"environments": {
		"production":{
			"healthcheck":{
				"url":"\/"
			}
		}
	}
}
```

- TCP Healthcheck to port 80 for single envionment `production`
```json
{
	"environments": {
		"production":{
			"healthcheck":{
				"url":"\/"
			}
		}
	}
}
```
