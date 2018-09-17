## Volumes
Does not yet use the MainServiceBuiltEvent.  
Meaning: Can only be used if the blueprint explicitly supports it.

Parses the key `volumes` and adds the given volumes to the service
### Possible Syntaxes:
Note: volume-name can always be a volume name(named volume) or a host path(host volume)
#### String
If the volume is given as string then the string is seen as the path inside the container while the array key is seen
as the volume-name
```json
"volumes":{
	"volume-name":"/path/in/volume"
}
```
#### Object
If the value is given as object then the key `path` is used for the path inside the container while the array key is used
as default volume-name.
Possible keys:
- `name` Overrides the array key as volume-name. Usually used if `volumes` is a json array
- `path` REQUIRED! Path inside the Container
- `driver` Volume driver to use for this volume. Defaults to `local`
- `driver-options` Pass additional driver options to the driver.
- `mount-options` Add additional mount options like `rw` or a mount propagation.
```json
"volumes":{
	"volume-name":{
	  "name":"volume-name",
	  "path":"/path/in/volume",
	  "mount-options":[
	 	 'rshared'
	  ],
	  "driver":"rancher-nfs",
	  "driver-options":{
	    "some-option":"1000"
	  }
	}
}
```
```json
"volumes":[
	{
	  "name":"volume-name",
	  "path":"/path/in/volume",
	  "driver":"rancher-nfs",
	}
    "driver-options":{
	  "some-option":"1000"
    }
]
```

### Known Blueprints to support this
- [WebserverBlueprint](../Webserver/README.md)
