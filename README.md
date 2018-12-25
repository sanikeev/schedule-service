# Сервис расписания
В реализации задачи использовал следующие технологии

* vuejs
* symfony 4.2
* docker-compose

## HOW TO

*ВАЖНО*
1. У вас должны быть установлены docker и docker-compose (хотя никто вам не мешает установить проект по старинке)
2. Должен быть свободен 80-й порт (если занят сконфигурируйте .docker/docker-compose.yaml на работу через другой порт)
3. Для того чтобы работать с изменениями в коде vue вам понадобиться установленный yarn

Это руководство я проверял на Ubuntu Linux если у вас другая операционная система, посмотрите руководство по докеру для вашей ОС.

1. Клонируем этот репозиторий к себе в папку  
`git clone git@github.com:sanikeev/discount-service.git vse`
2. Запускаем докер
`$ sudo docker-compose -f .docker/docker-compose.yaml up -d`
3. Устанавливаем зависимости
`$ sudo docker exec -i vse-fpm composer install`
4. Разворачиваем схему БД
`$ sudo docker exec -i vse-fpm php bin/console doctrine:schema:update --force`
5. Загружаем подготовленные фикстуры в БД
`$ sudo docker exec -i vse-fpm php bin/console doctrine:fixtures:load`
6. Запускаем команду генерации расписания с 2015 года:
`$ sudo docker exec -i vse-fpm php bin/console schedule:fill`
7. Открываем проект в браузере по адресу `http://vse.lvh.me`

## Чему в проекте не уделил внимание

* Клиентской части. 
* На бэкенде так же не сделал проверку на валидность данных
* В формах нет вывода ошибок заполнения.
