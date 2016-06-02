server {
	listen 80 default_server;
	listen 81 default_server http2 proxy_protocol;
	listen [::]:80 default_server ipv6only=on;

	root /var/www/laravel/public;
	index index.php index.html index.htm;

	server_tokens off;

	error_log    /var/log/nginx/error.log debug;
	rewrite_log on;

	server_name <SERVER_URL>;

	### HTTPS Rewrite

	#
	# Der rewrite hier ist etwas komplizierter, damit die Unittests funktionieren
	# Grundrewrite: Wenn am Loadbalancer nicht mit https angefagt wird dann leite auf https weiter
	# Problem: Unittests haben keinen Loadbalancer zwischen sich und dem Server
	# Lösung: Lokale Adressen werden vom rewrite ausgenommen
	# Achtung: Zugriffe in der lokalen dev Umgebung zählen als von außen kommend, weil diese von der `öffentlichen` Container-
	#          Adresse reinkommen.
	#

	# Grundstatus: Request kommt von außen
	set $test "REMOTE";

	# Bedingung: Wenn die ipv4 localhost Adresse mitgegeben wurde dann haben wir einen lokalen request
	if ($remote_addr = "127.0.0.1") {
		set $test "LOCAL";
	}

	# Bedingung: Wenn die ipv6 localhost Adresse mitgegeben wurde dann haben wir einen lokalen request
	if ($remote_addr = "::1") {
		set $test "LOCAL";
	}


	# Bedingung: Wenn das Anfordernde Protokol am Loadbalancer nicht https ist
	if ($http_x_forwarded_proto != "https") {
		set $test "${test}PROTO";
	}

	# Bedingung: Wenn der Request am Loadbalancer nicht mit https ankam UND es sich nicht um einen lokalen request handelt
	# leite um auf https.
	# Die Abfrage auf den zusammengesetzten String ist ein pseudoe AND. Nginx unterstützt keine und/oder in if Bedingungen
	if ($test = REMOTEPROTO) {
		rewrite  ^/(.*)$  https://$host/$1 permanent;
	}
	### HTTPS Rewrite Ende

	if (!-d $request_filename) {

		rewrite ^/(.+)/$ /$1 permanent;

	}

	location / {
		try_files $uri $uri/ /index.php$is_args$args;
	}

	location ~ \.php$ {

		try_files $uri /index.php =404;
		fastcgi_split_path_info ^(.+\.php)(/.+)$;
		fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
		fastcgi_index index.php;
		fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
		include /etc/nginx/fastcgi_params;
	}
}

