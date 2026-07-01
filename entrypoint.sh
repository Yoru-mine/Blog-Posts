#!/bin/bash
php artisan config:clear
php artisan config:cache
apache2-foreground
