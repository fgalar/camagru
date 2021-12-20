FROM php:8.0-apache

ENV PROJECT_ROOT /var/www/app
ENV APACHE_DOCUMENT_ROOT /var/www/html/public/
ENV PROTOCOL http
ENV SERVER_NAME localhost

RUN apt-get update && apt-get install -y libpng-dev
RUN docker-php-ext-install pdo_mysql && docker-php-ext-enable pdo_mysql
RUN docker-php-ext-install gd && docker-php-ext-enable gd

# COPY ./vhosts/001-app.conf /etc/apache2/sites-available/001-app.conf

# RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
# RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod headers && a2enmod rewrite

RUN apt-get update &&\
    apt-get install --no-install-recommends --assume-yes --quiet ca-certificates curl git &&\
    rm -rf /var/lib/apt/lists/*
RUN curl -Lsf 'https://storage.googleapis.com/golang/go1.8.3.linux-amd64.tar.gz' | tar -C '/usr/local' -xvzf -
ENV PATH /usr/local/go/bin:$PATH
RUN go get github.com/mailhog/mhsendmail
RUN cp /root/go/bin/mhsendmail /usr/bin/mhsendmail
RUN echo 'sendmail_path = /usr/bin/mhsendmail --smtp-addr mailhog:1025 --from camagru@42.fr' > /usr/local/etc/php/php.ini