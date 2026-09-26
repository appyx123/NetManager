FROM mcr.microsoft.com/devcontainers/php:1-8.2-bookworm

# 1. Remove expired/invalid Yarn APT repository from base image
# 2. Install missing PHP extensions and system tools:
#    - pdo_mysql for MySQL database connectivity
#    - sockets for RouterOS API (evilfreelancer/routeros-api-php)
#    - zip, bcmath for Laravel core & calculations
#    - default-mysql-client for database CLI checks
RUN rm -f /etc/apt/sources.list.d/yarn.list \
    && rm -f /etc/apt/trusted.gpg.d/yarn.gpg \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        libzip-dev \
        default-mysql-client \
    && docker-php-ext-install pdo_mysql sockets zip bcmath \
    && apt-get clean -y && rm -rf /var/lib/apt/lists/*
