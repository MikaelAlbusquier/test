timeout_test_mikael
===================

Prerequisite:
1) You'll need to have composer installed (https://getcomposer.org/download/)
2) You'll need to have MySQL installed

How to install:
1) Run 'git clone https://github.com/MikaelAlbusquier/test.git'
2) Run 'cd test'
3) Run 'composer install', you'll see packages getting installed and you'll be asked to set your parameters at the end (don't mind the unknown database messages it gets set automatically at the next step)
4) Run './setup.sh'
5) In your web browser, enter the following url : http://127.0.0.1:8000
6) Run ./clear.sh if you want to stop the web server and drop the database