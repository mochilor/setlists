FROM phpdockerio/php72-fpm:latest

RUN apt-get update && apt-get -y --no-install-recommends install php7.2-mysql php7.2-bcmath php7.2-xdebug php7.2-memcached git zip unzip gnupg\
&& curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer\
&& curl -sL https://deb.nodesource.com/setup_10.x | bash -\
&& apt-get install -y nodejs build-essential