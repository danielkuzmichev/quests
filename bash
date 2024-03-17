#!/usr/bin/env bash

# имя функции для запуска
if [ ! -z $1 ]; then
    FUNCTION="$1"
fi

#HELP
showHelp(){
  echo -e "start - запустить докер"
  echo -e "stop - остановка докера"
}

#Остановка Docker
stop(){
  docker stop $(docker ps -a -q)
}

#Запуск Docker
start(){
  docker-compose up -d
}


if [ ! -z $(type -t $FUNCTION | grep function) ]; then

     $1
 else
     showHelp
fi
