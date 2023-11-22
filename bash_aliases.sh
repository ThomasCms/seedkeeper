#!/usr/bin/env sh
alias sk-composer='docker-compose run --rm php-fpm composer'

alias sk-php='docker-compose run --rm php-fpm php'

alias sk-sf='docker-compose run --rm php-fpm php bin/console'

alias sk-chmod-app='docker-compose run --rm php-fpm chmod -R 777 var/ config/'

alias sk-assets='npm run dev'

alias sk-npm-dev='npm run watch'