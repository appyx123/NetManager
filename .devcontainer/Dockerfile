FROM mcr.microsoft.com/devcontainers/php:1-8.2-bookworm

# 1. Fix: Remove expired/invalid Yarn APT repository from base image
# 2. Install missing PHP extensions required by composer dependencies:
#    - evilfreelancer/routeros-api-php requires ext-sockets
#    - laravel/dusk & php-webdriver require ext-zip
RUN rm -f /etc/apt/sources.list.d/yarn.list \
    && rm -f /etc/apt/trusted.gpg.d/yarn.gpg \
    && apt-get update \
    && apt-get install -y --no-install-recommends libzip-dev \
    && docker-php-ext-install sockets zip \
    && apt-get clean -y && rm -rf /var/lib/apt/lists/*
