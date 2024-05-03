#!/bin/bash

# BEGIN: Setup Commands
echo "Setting up Tickletime Rentals..."

# NPM INSTALL
echo "Running 'npm install'..."
npm install

# COMPOSER INSTALL
echo "Running 'composer install'..."
composer install

# COPY .ENV
echo "Copying .env.example"
cp .env.example .env

# RUN MIGRATIONS
echo "Running migrations"
php artisan migrate:fresh --seed

# LINK STORAGE
echo "Linking storage"
php artisan storage:link

# END: Setup Commands
echo "Tickletime Rentals setup complete."
