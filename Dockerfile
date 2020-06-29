# docker build . -t my-php-app:1.0.0

FROM php:7.2-apache
COPY src/ /var/www/html
COPY --from=lachlanevenson/k8s-kubectl:v1.10.3 /usr/local/bin/kubectl /usr/local/bin/kubectl
RUN apt-get update && apt-get -y install cron
EXPOSE 80
