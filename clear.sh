#!/bin/bash

# include parse_yaml function
. parse.sh

# read yaml file
eval $(parse_yaml "app/config/parameters.yml" "config_")

php bin/console doctrine:schema:drop --force

# Drop mysql database
if [ $config_parameters__database_password == "null" ]
then
    mysql -u$config_parameters__database_user --execute="drop database $config_parameters__database_name"
else
    mysql -u$config_parameters__database_user -p$config_parameters__database_password --execute="drop database $config_parameters__database_name"
fi

php bin/console server:stop
