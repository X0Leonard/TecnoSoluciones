FROM php:8.2-fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && apt-get install -y nginx && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/

RUN echo 'server { \n\
    listen 80; \n\
    root /var/www/html; \n\
    index index.php index.html; \n\
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg)$ { \n\
        expires max; \n\
        log_not_found off; \n\
    } \n\
    location / { try_files $uri $uri/ =404; } \n\
    location ~ \.php$ { \n\
        fastcgi_pass 127.0.0.1:9000; \n\
        fastcgi_index index.php; \n\
        include fastcgi_params; \n\
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \n\
    } \n\
}' > /etc/nginx/sites-available/default

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD php-fpm -D && nginx -g "daemon off;"