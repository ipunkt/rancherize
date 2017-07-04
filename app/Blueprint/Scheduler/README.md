# Scheduler
Sets scheduling rules for the container.

## Use case
Rancher uses Tag based scheduling which is set via container labels.

## Options
All options life inside the `scheduler` json object.

- `enable`: Can be set to `false` to disable creating the scheduling rules without removing them. Defaults to `true`
- `scheduler`: Scheduler to use. Currently the default and only implementation available is `rancher`
- `same-host`: Setting this to `true` will allow multiple containers to be scheduled on the same host. Defaults to `false`
- `tags`: Object or array of tags. A host must have these tags set for a container to be scheduled there.  Defaults to []
   - Arrays or numerical object variables will be created as `$VALUE=true` tag rule
   - non-numerical object variables will be created as `$NAME=$VALUE` tag rule
- `forbid-tags`: Object or array of forbidden tags. see `tags` except the host must NOT have these tags. Defaults to []


## Example
- Schedule all environments on hosts which have the tag `apps=true` and prefer hosts which do not have `fallback=true`
```json
{
	"defaults":{
		"scheduler":{
			"tags":[
				"apps",
			],
			"should-not-have-tags":[
				"fallback"
			]
		}
	}
}
```
- Schedule the single environment `test` on hosts which have the tag `apps=true` and prefer hosts which do not have `fallback=true`
```json
{
	"environments":{
		"test":{
			"scheduler":{
				"tags":[
					"apps",
				],
				"should-not-have-tags":[
					"fallback"
				]
			}
		}
	}
}
```
