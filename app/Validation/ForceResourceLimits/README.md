# force resource limits
Set the `REQUIRE_RESOURCE_LIMIT` environment variable to anything non-empty to active resource limit enforcement.

If set then any step that requires a validation of the configuration will fail if no resource-limit is present.

## Use case
Apps without resource limits are bad because without resource limits every app has the potential to use up all its hosts
resources. Also because Rancher doesn't have an indication how much resources the app needs it will potentially schedule
3 apps which all want 3GB Ram on a 1GB Ram machine.
To prevent yourself from forgetting to set resource-limits you can now set this.
