# Stackoverflow Questions API

Prerequisites
===================

- PHP 7.4
- Symfony 5.3.*
- Docker + Docker Compose

Install
===================

### Clone

```sh
$ git clone https://github.com/epuig83/stackoverflow-questions.git
$ cd stackoverflow-questions
```

### Build up all containers

```sh
$ docker-compose build
$ docker-compose up -d
```

### Composer dependencies

```sh
$ docker-compose exec webserver composer install --no-interaction
```

### Access to bash webserver container

```sh
$ docker exec -it webserver bash
```


Testing
===================
Run tests

```sh
$ docker-compose exec webserver php ./vendor/bin/phpunit
```

Questions Endpoint
===================

```console
http://localhost:8080/api/stackoverflow/questions/{tagged}
```


|          Name  | Required |  Type   | Description                                                                                               |
| --------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------- |
|     `tagged`   | required | string  | The question tag                                                                                          |
|     `fromdate` | optional | string  | The date format is accepted: YYYY-MM-DD HH:MM:SS, where the hour, minute, and second values are optional. |
|     `todate`   | optional | string  | The date format is accepted: YYYY-MM-DD HH:MM:SS, where the hour, minute, and second values are optional. |

