# Blueprints

## Adding a Blueprint
The command `blueprint:add` exists to register a blueprint to the list of known blueprints.

	vendor/bin/rancherize blueprint:add {name} {classpath}
	
	e.g.
	vendor/bin/rancherize blueprint:add webserver 'Rancherize\Blueprint\Webserver\WebserverBlueprint'

## Distributing a Blueprint
I recommend distributing blueprints as composer packages and providing the required `blueprint:add` command in the
README.md

In the future a support command will be added to install a given composer package and add the blueprint from a file
located in the package.

## Writing a Blueprint

A Blueprint is written by implementing the interface `Rancherize\Blueprint\Blueprint`.

### Helper
The configuration passed to the Blueprint encompasses the complete configuration: global and project, including all
environments. Blueprints usualy only deal with the current environment and the project defaults so using these helpers
make the code easier to write and read

- `Rancherize\Configuration\PrefixConfigurableDecorator`: Creates a new configurable that accesses values in the
configuration prefixed by a given string
- `Rancherize\Configuration\Services\ConfigurableFallback`: Creates a configurable where get and has will first try a
primary configuration then fall back a secondary configuration if the value was not found there.

```php
$environmentConfigurable = new PrefixConfigurableDecorator($configurable, "project.environments.$environment.");
$projectConfigurable = new PrefixConfigurableDecorator($configurable, "project.default.");
$fallbackConfigurable = new ConfigurableFallback($environmentConfigurable, $projectConfigurable);
```

- environmentConfigurable will get and set values inside the environment to be written
- projectConfigurable will get and set values inside the project defaults
- fallbackConfigurable will get values from the environment. If it is not found there it will get them from the project
defaults. Values set here will be written to the environmentConfiguration

### setFlag
This method is used to pass flags from the init command to the Blueprint. Currently only the --dev flag for the init
command will be set.

#### Helper
A trait is available to implement this for you: `Rancherize\Bluerprint\Flags\HasFlagsTrait` provides the method
`getFlag($flag, $default = null)` method and saves to the protected property $flags

### init
The init method receives the complete configuration and the name of the environment to be initialized. It should set at
least all configuration values required to pass validation.

If not flags are present then an environment fit for use within rancher should be created  
If the --dev flag is present then ane environment fit for local development work should be created

User interaction is possible using the passed Symfony2 `InputInterface` and `OutputInterface` but should be kept to a
minimum. Prefer setting the variable to an invalid explanatory value over asking the user a value with an explanation.

	$configuration->set("example-var", "This variable is an example and should be set to 'test'");

rather than

	$question = new Question("Value for example-var: ");
	$configuration->set("example-var", $questionHelper->ask($question));
	
#### Helper
A helper is available to allow you to focus on what to initialize rather than how to do it.

- `Rancherize\Configuration\Services\ConfigurationInitializer` Will only set a given value in the configuration if it is
not yet present. It will also inform the user about it based on the output verbosity

Example usage:
```php
$initializer = new ConfigurationInitializer($output);
$initializer->init($fallbackConfigurable, 'written-to-environment', "value");
$initializer->init($fallbackConfigurable, 'written-to-defaults', "value", $projectConfigurable);
```
### validate

The validate method should ensure that the minimum configuration values necessary are present and if possible that the
set configuration values are valid where set.

If the validation is not successful the method is expected to throw an exception of type
`Rancherize\Blueprint\Validation\Exceptions\ValidationFailedException`

#### Helper

- `Rancherize\Blueprint\Validation\Traits\HasValidatorTrait`: Provides a validator using `$this->getValidator()->validate($config, ['field' => 'rule'])`  
  Currently only the `required` rule is available

Example Usage:
```php
$this->getValidator()->validate($config, [
	'docker.base-image' => 'required',
	'service-name' => 'required',

]);
```

### Build

The build method is supplied the current configuration, the name of the environment to build for and possibly a version
to build for. It must build and return a `Rancherize\Blueprint\Infrastructure\Infrastructure` consisting of the
Dockerfile used to build the docker-image and all services required to start the infrastructure.

It is recommended to create and reuse concrete `Service` classes for images instead of setting all values within the build
method.

See the [Dockerfile](Infrastructure/Dockerfile/Dockerfile.php) and [Service](Infrastructure/Service/Service.php) classes
for the available options to build them
