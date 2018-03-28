#!/bin/bash

# include parse_yaml function
. parse.sh

# read yaml file
eval $(parse_yaml "app/config/parameters.yml" "config_")

# Create mysql database
if [ $config_parameters__database_password == "null" ]
then
    mysql -u$config_parameters__database_user --execute="create database $config_parameters__database_name"
else
    echo "mysql -u$config_parameters__database_user -p$config_parameters__database_password --execute='create database $config_parameters__database_name'"
fi

# create database schema
php bin/console doctrine:schema:create
# populate database from json files
php bin/console timeout:populate-database
# Run web server
php bin/console server:start
