#!/bin/sh
#
# Get Weather from Netduino
#
while (sleep 10 && php /var/www/revyweather/public/index.php getweatherdata) &
do
    wait $!
done