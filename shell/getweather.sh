#!/bin/sh
#
# Get Weather from Netduino
#
while (sleep 10 && /usr/local/zend/bin/php /usr/local/zend/var/apps/http/__default__/0/0.1/public/index.php getweatherdata) &
do
    wait $!
done