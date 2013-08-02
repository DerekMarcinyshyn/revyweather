/**
 * Main weather data
 * @author      Derek Marcinyshyn <derek@marcinyshyn.com>
 * @date        July 31, 2013
 */
$(document).ready(function() {
    var interval = 30000; // number of milliseconds to refresh

    var g = new JustGage({
        id: "gauge",
        value: 0,
        min: 0,
        max: 60,
        title: "Wind Speed"
    });

    var refresh = function() {
        $.getJSON('/data/json/current.json.php', function(data) {

            g.refresh(data.speed);

            $('#data').html('tempdht: ' + data.tempdht +
                '  humiditydht: ' + data.humiditydht +
                '  tempbmp: ' + data.tempbmp +
                '  pressurebmp: ' + data.pressurebmp +
                '  direction: ' + data.direction +
                '  speed: ' + data.speed);
        });
    };

    setInterval(refresh, interval);

    refresh();
});