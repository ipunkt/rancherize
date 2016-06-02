FROM busybox
ADD . /var/www/laravel
VOLUME /var/www/laravel
ADD nginx-conf/laravel.conf.tpl /etc/nginx/conf.template.d/
VOLUME /etc/nginx/conf.template.d
RUN sh -c 'if [ -f "/var/www/laravel/docker-prepare.sh" ] ; then sh /var/www/laravel/docker-prepare.sh ; fi'
CMD /bin/dc
