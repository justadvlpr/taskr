#!/usr/bin/env bash

# exit in case anything goes wrong
set -e

# Export all variables into a file, usefull for the cronjob.
# Only variables that start with "APP_".
# without quotes: sed 's/^\(.*\)$/export \1/g'
# with quotes: sed 's/^\([a-zA-Z0-9_]*\)=\(.*\)$/export \1="\2"/g'
# first export variables from .env file
cat /var/www/html/.env | sed 's/^\([a-zA-Z0-9_]*\)=\(.*\)$/export \1="\2"/g' | grep -E "^export APP_" > /.env
# append server variables to override the ones from .env file
# ex: if you set variables at (rancher/docker/docker-compose) level they need to be set after -
# in order to override the ones from the .env file.
printenv | sed 's/^\([a-zA-Z0-9_]*\)=\(.*\)$/export \1="\2"/g' | grep -E "^export APP_" >> /.env

php /var/www/html/vendor/bin/yii migrate/up

crontab -u taskr /var/spool/cron/crontabs/taskr
/etc/init.d/cron start

apache2-foreground
