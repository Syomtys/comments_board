### Доска комментариев пользователей

##### Компоненты разработки:
- MacOS 14.4.1
- PHP 7.4 (+ установки элентов для работы с базой и картинками(в dockerfile))
- MySQL 5.7
- Docker 4.26.0
- MVC модель PHP
- frontend: (HTML, CSS, JS, jQuery)

Сборка докер образа:
`docker compose up -d`

Переход в php оболочку по необходимости:
`docker exec -w /var/www/html/ -it smt-project-php /bin/sh`

#### Этот проект представляет собой веб-приложение доски комментариев пользователей. В приложении реализованы следующие основные функциональности:

### Фильтрация комментариев:
- Пользователи могут фильтровать комментарии по имени, электронной почте и дате.

### Добавление новых комментариев:
- Пользователи могут добавлять новые комментарии, указывая своё имя, электронную почту и текст комментария.
Для защиты от спама предусмотрена капча, а также собирается IP-адрес и информация о браузере пользователя.

