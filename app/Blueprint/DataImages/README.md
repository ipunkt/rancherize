# data-images
Data images add containers with the given image as sidekick to all services, adding the to the volumes-from list.

## Use case
One possible use case for this is requiring tls certificates to connect to a mysql server. Build an image with the images
in /mysql/certificates and VOLUME on /mysql/certificates. Then add the the (private repostory) image as data-image.

-> Your app will find the certificates in /mysql/certificates

## Example
```json
{
	"data-images":[
		"registry.gitlab.com/organization/image:version"
	]
}
```