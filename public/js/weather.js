/**
 * Main weather data
 * @author      Derek Marcinyshyn <derek@marcinyshyn.com>
 * @date        July 31, 2013
 */
$(document).ready(function() {
    var interval = 10000; // number of milliseconds to refresh

    var refresh = function() {
        $.getJSON('/data/json/current.json.php', function(data) {
            var g = new JustGage({
                id: "gauge",
                value: data.speed,
                min: 0,
                max: 60,
                title: "Wind Speed " + data.speed +  " km/h"
            });

            $('#data').append('tempdht: ' + data.tempdht +
                '  humiditydht: ' + data.humiditydht +
                '  tempbmp: ' + data.tempbmp +
                '  pressurebmp: ' + data.pressurebmp +
                '  direction: ' + data.direction +
                '  speed: ' + data.speed);
        });
    };

    refresh();
});