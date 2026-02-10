FROM dunglas/frankenphp:latest

# Instalar extensiones necesarias para MySQL
RUN install-php-extensions mysqli pdo_mysql

WORKDIR /app
COPY . /app
