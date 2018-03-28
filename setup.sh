#!/bin/bash
php bin/console doctrine:schema:create

php bin/console timeout:populate-database
