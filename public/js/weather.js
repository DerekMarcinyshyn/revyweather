/**
 * Main weather data
 * @author      Derek Marcinyshyn <derek@marcinyshyn.com>
 * @date        July 31, 2013
 */
$(document).ready(function() {
    var interval = 10000; // number of milliseconds to refresh

    var g = new JustGage({
        id: "gauge-speed",
        value: 0,
        min: 0,
        max: 60,
        title: "Wind Speed"
    });

    var refresh = function() {
        $.getJSON('/data/json/current.json', function(data) {

            g.refresh(data.speed);

            $('#timestamp').html(data.timestamp);
            $('#temp').html(data.temp + ' &deg;C');
            $('#pressure').html('Pressure: <strong>' + data.pressurebmp + ' kPa</strong>');
            $('#humidity').html('Humidity: <strong>' + data.relativehumidity + '%</strong>');
            $('#wind').html('Wind: <strong>' + data.direction + ' ' + data.speed + ' km/h</strong>');

            var degrees = 0;

            switch (data.direction) {
                case "N":
                    degrees = 270;
                    break;
                case "NE":
                    degrees = 315;
                    break;
                case "E":
                    degrees = 0;
                    break;
                case "SE":
                    degrees = 45;
                    break;
                case "S":
                    degrees = 90;
                    break;
                case "SW":
                    degrees = 135;
                    break;
                case "W":
                    degrees = 180;
                    break;
                case "NW":
                    degrees = 225;
                    break;
            }

            $('#arrow').css("-webkit-transform", "rotate("+degrees+"deg)")
                .css("-moz-transform", "rotate("+degrees+"deg)")
                .css("-o-transform", "rotate("+degrees+"deg)")
                .css("-ms-transform", "rotate("+degrees+"deg)");
        });
    };

    setInterval(refresh, interval);

    refresh();
});