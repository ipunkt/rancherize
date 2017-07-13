# NginxSnippets
Parses `nginx.enable` and `nginx.snippets` via NginxSnippetParser and adds information to a service.
Makes `/etc/nginx/server.d` a volume in the built app container and copies all files given in `nginx.snippets` into there
