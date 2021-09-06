### Requirements
- Docker
- docker-compose

### Installation
Execute from /docker folder
```shell
$ make install
```

### Parse news
Execute from /docker folder
```shell
$ docker-compose exec php-fpm php artisan parse:news rbk
```
Class where this command handled - laravel/app/Console/Commands/ParseNews.php
