# PublishUrls
Takes care of setting docker environment variables / labels to request the publish of a given domain(and path) to the
rancher service spawned by rancherize.  
These settings have to be picked up by service discovery. The `traefik` rancher load balancer does this on its own, based
on labels.

# List of implementations
- traefik

## Use case
The rancher traefik loadbalancer publishes service ports based on a list of labels:
- traefik.enable
- traefik.domain
- traefik.alias
- traefik.path
- traefik.path.prefix - traefik.priority

## Example
```json
{
	"default":{
		"healthcheck":{
			"enable":true
		},
		"publish":{
			"enable": true,
			"url": "https:\/\/www.example.com"
		}
	},
	"environments":{
		"test": {
			"publish": {
				"pathes": [
					"\/sitemaps\/",
					"\/sitemap.xml"
				]
			}
		}
	}
}
```
Will set the traefik labels to balance the pathes `sitemap.xml,/sitemaps/` on `www.example.com` to the service.
The protocol is currently ignored.  
Since no port is given port 80 is assumed

## All options
All options are inside the `publish` json object
- `type`: The type of loadbalancer / service discovery to create info for. Currently only `traefik` is available and is used
  as default.
- `port`: The port inside the container that should be published through the load balancer. Defaults to `80`
- `pathes`: If this is non-empty then only http urls with pathes starting with the given path are forwarded to this container. Defaults to `[]`
- `priority`: High priority services beat lower priority services.

### Traefik implementation notes
- The default priority is 5. Setting `pathes` will change the default priority to `10`.
- Rancher traefik only accepts containers in the `healthy` state - this state only appears when a healthcheck is set. This mean
  setting a [healthcheck](../Healthcheck/README.md) is NECESSARY for the published urls to work.  
  TODO: Add check for Loadbalancer to the valdation.
