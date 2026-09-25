FROM mcr.microsoft.com/devcontainers/php:1-8.2-bookworm

# Fix: Remove expired/invalid Yarn APT repository from base image
# Project uses npm; removing yarn.list prevents NO_PUBKEY 62D54FD4003F6525 failure during feature installs
RUN rm -f /etc/apt/sources.list.d/yarn.list \
    && rm -f /etc/apt/trusted.gpg.d/yarn.gpg \
    && apt-get update \
    && apt-get clean -y && rm -rf /var/lib/apt/lists/*
