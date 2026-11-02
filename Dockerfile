# syntax=docker/dockerfile:1

# One image runs every App Platform component (web, queue, scheduler, migrate job):
# the web service uses the default command, the others override it (see .do/app.yaml).
# https://serversideup.net/open-source/docker-php/docs

ARG PHP_IMAGE=serversideup/php:8.4-fpm-nginx-v5.0.1
ARG NODE_IMAGE=node:22-alpine

############################################
# Base: PHP + the extensions the app needs on top of the image defaults
# (pdo_mysql, redis, zip, pcntl and opcache ship with it).
############################################
FROM ${PHP_IMAGE} AS base

USER root
# bcmath: moneyphp (Cashier), gd/exif: image handling, intl: number/locale formatting
RUN install-php-extensions bcmath gd exif intl
USER www-data

############################################
# Composer dependencies (throwaway stage, so it runs as root)
############################################
FROM base AS vendor

USER root
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction --no-progress

COPY . .
# Also runs package:discover, so bootstrap/cache only lists production packages.
RUN composer dump-autoload --optimize --no-dev --no-interaction

############################################
# Frontend assets
############################################
FROM ${NODE_IMAGE} AS assets

WORKDIR /app
# puppeteer is a devDependency; the build never needs its Chrome download.
ENV PUPPETEER_SKIP_DOWNLOAD=true

# VITE_* values are compiled into the JS bundle, so they are build args, not runtime env.
ARG VITE_PUSHER_APP_KEY=""
ARG VITE_PUSHER_APP_CLUSTER="mt1"
ENV VITE_PUSHER_APP_KEY=${VITE_PUSHER_APP_KEY} \
    VITE_PUSHER_APP_CLUSTER=${VITE_PUSHER_APP_CLUSTER}

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

# PurgeCSS scans app/ and resources/views, so both have to be in the build context.
COPY vite.config.js ./
COPY resources ./resources
COPY app ./app
RUN npm run build

############################################
# Production
############################################
FROM base AS production

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    PHP_OPCACHE_ENABLE=1 \
    AUTORUN_ENABLED=true \
    AUTORUN_LARAVEL_MIGRATION=false

COPY --chmod=755 docker/entrypoint.d/ /etc/entrypoint.d/
COPY --from=vendor --chown=www-data:www-data /var/www/html /var/www/html
COPY --from=assets --chown=www-data:www-data /app/public/build /var/www/html/public/build
