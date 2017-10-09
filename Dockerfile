FROM laradock/workspace

RUN apt-get update && \
    apt-get install -y php7.0-xdebug && \
    sed -i 's/^/;/g' /etc/php/7.0/cli/conf.d/20-xdebug.ini
