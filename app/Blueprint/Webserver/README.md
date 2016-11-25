## WebserverBlueprint

This blueprint creates infrastructures to support apps using php7.

### Typical Infrastructure

![typical infrastructure](typical-infrastructure.jpg)

![typical main app](typical-app-service.jpg)


### Recognized Configuration Values

| Option | Defaults to | Explanation |
| ------- |:-----------:| ------------ |
|`docker.base-image`| REQUIRED | Docker-Image to base the created app-image on. |
|`service-name`| REQUIRED | The name of the created main service, will have the version appended to it in rancher |
|`use-app-contianer`| true | If set to false no service will be created to mount the work directory using a docker image |
|`mount-workdir`| false | If set to true then the project root will be mounted into the main app nginx. !Does not work when deploying into rancher! |
|`expose-port`|  | if set then the Port 80 of the nginx container will be exposed to this host port |
|`nginx-config`|  | If set to the path of a file relative to the app work directory then the file will be used by the main app nginx |
| `environment` | [] | Any property set in this object will be passed to the running container as shell environment variable `PROPERTNAME=PROPERTYVALUE`. Note that environment values set in the `defaults` can be overwritten but not un-set |
|`add-redis`| false | Add a Redis server and link it to the main app, providing its name and port in `REDIS_HOST` and `REDIS_PORT` |
|`add-database`| false | If set to true then a database server will be started as part of the stack and linked to the main app. Database name, user and password can be found in `DATABASE_NAME`, `DATABASE_USER` and `DATABASE_PORT` |
|`database.name`| db | Sets the name of the default database created by the database container |
|`database.user`| user | Sets the name of the default user created by the database container |
|`database.password`| pw | Sets the name of the default password created by the database container |
|`database.pma`| true | !Only effective if add-databse is true! If set to true then a phpmyadmin container is started and connected to the database container |
|`database.expose`| true | Can be set to false to prevent exposing the internal pma port 80 to a host port. |
|`database.pma-port`| 8082 | Host port to expose the pma container port 80 to. |
