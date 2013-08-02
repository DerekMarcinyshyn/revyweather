#!/bin/sh
#
# Get Weather from Netduino
#
while (sleep 10 && php /home/web/public_html/revyweather/public/index.php getweatherdata) &
do
    wait $!
done