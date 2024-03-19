## Запуск
1. в папке проекта при включеном докере собираем контейнеры:

docker-compose up --build -d

2. запускаем терминал в контейнере

docker exec -it App bash

3. скачиваем зависимости:

composer install
