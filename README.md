# Tokenbox Core

## О проекте

Данный проект является библиотекой и внешним подключаемым репозиторием для проекта https://github.com/src83/tokenbox
Единственная его цель - сгенерировать 1 млрд уникальных, хорошо перемешанных токенов и загрузить их в БД ClickHouse.

## Текущая структура папок
```bash
└─ app
   ├─ lib
   ├─ src
   │  ├─ DTO
   │  │  ├─ Calc
   │  │  └─ Generator
   │  └─ Utils
   │     ├─ Calc
   │     └─ Generator
   └─ tests
```

## Установка

1. В файле `composer.json` (в корне целевого проекта):

    * В секцию "require" добавить:
       ```
       "src83/tokenbox-core": "dev-master",
       "smi2/phpclickhouse": "^1.5"

    * В корень добавить секцию (внешний репозиторий):
       ```
       "repositories": [
            {
                "type": "vcs",
                "url": "git@github.com:src83/tokenbox-core.git"
            }
       ],

2. Выполнить `composer install`

