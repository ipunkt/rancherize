# PublishUrls
Takes care of setting docker 

## Use case
The rancher traefik loadbalancer publishes service ports based on a list of labels:
- traefik.enable
- traefik.domain
- traefik.alias
- traefik.path
- traefik.path.prefix
- traefik.priority

Setting
```json
{
	"publish-urls": {
		"enable": true,
		"urls":[
			"https://www.example.com/sitemap.xml",
			"https://www.example.com/sitemaps/"
		]
	}
}
```
Will set the traefik labels to balance the pathes `sitemap.xml,/sitemaps/` on `www.example.com` to the service.
The protocol is currently ignored.  
Since no port is given port 80 is assumed
