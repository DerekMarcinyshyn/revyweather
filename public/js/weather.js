/**
 * Main weather data
 * @author      Derek Marcinyshyn <derek@marcinyshyn.com>
 * @date        July 31, 2013
 */
jQuery(document).ready(function($) {
    var interval = 10000; // number of milliseconds to refresh

    var g = new JustGage({
        id: "gauge-speed",
        value: 0,
        min: 0,
        max: 20,
        title: "Wind Speed"
    });

    var refresh = function() {
        $.getJSON('/data/json/current.json', function(data) {

            var windSpeed = (data.speed * 1.60934);
            windSpeed = Math.ceil(windSpeed * 10) / 10;
            g.refresh(windSpeed);

            $('#timestamp').html(data.timestamp);
            $('#temp').html(data.bmp_temperature + ' &deg;C');
            $('#pressure').html('Pressure: <strong>' + data.barometer + ' kPa</strong>');
            $('#humidity').html('Humidity: <strong>' + data.relativehumidity + '%</strong>');
            $('#wind').html('Wind: <strong>' + data.direction + ' ' + windSpeed + ' km/h</strong>');

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

    // get the latest webcam image
    var camInterval = 180000;
    var src = 'http://www.revyweather.com/img/latest.jpg';
    var refreshLatest = function(){
        $('#webcam-latest').attr('src', src);
    };

    setInterval(refreshLatest, camInterval);

    // create the weather maps
    var zoom = 4;
    var proj4326 = new OpenLayers.Projection("EPSG:4326");
    var projmerc = new OpenLayers.Projection("EPSG:900913");
    var lonlat = new OpenLayers.LonLat(-118, 49);
    lonlat.transform(proj4326, projmerc);

    var map = new OpenLayers.Map("map-container");

    // add google streets to base layer
    var gmap = new OpenLayers.Layer.Google("Google Maps");

    var layer_cloud = new OpenLayers.Layer.XYZ(
        "clouds",
        "http://${s}.tile.openweathermap.org/map/clouds/${z}/${x}/${y}.png",
        {
            isBaseLayer: false,
            opacity: 0.7,
            sphericalMercator: true
        }
    );

    var layer_precipitation = new OpenLayers.Layer.XYZ(
        "precipitation",
        "http://${s}.tile.openweathermap.org/map/precipitation/${z}/${x}/${y}.png",
        {
            isBaseLayer: false,
            opacity: 0.7,
            sphericalMercator: true
        }
    );

    var pressure_contour = new OpenLayers.Layer.XYZ(
        "Pressure",
        "http://${s}.tile.openweathermap.org/map/pressure_cntr/${z}/${x}/${y}.png",
        {
            numZoomLevels: 19,
            isBaseLayer: false,
            opacity: 0.4,
            sphericalMercator: true

        }
    );
    pressure_contour.setVisibility(false);

    map.addLayers([gmap, layer_cloud, layer_precipitation, pressure_contour]);
    map.addControl(new OpenLayers.Control.LayerSwitcher());
    map.setCenter(lonlat, zoom);

    // High Charts
    var chart;

    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });

    var options = {
        chart: {
            renderTo:           'high-charts-container',
            zoomType:           'x',
            backgroundColor:    '#F9F9F9'
        },

        title: {
            text:       'Courthouse Area Historical Weather'
        },

        xAxis: {
            type:       'datetime',
            dateTimeLabelFormat: {
                hour: '%m %d %H:%M'
            },
            tickPixelInterval: 150,
            minRange:   1
        },

        yAxis: [{
            gridLineWidth: 1,
            labels: {
                style: {
                    color: '#B26969'
                },
                format: '{value} °C'
            },
            title: {
                text: 'Temperature',
                style: {
                    color: '#B26969'
                }
            }
        }, {
            gridLineWidth: 0,
            title: {
                text: 'Rainfall',
                style: {
                    color: '#C7D1ED'
                }
            },
            labels: {
                format: '{value} mm',
                style: {
                    color: '#C7D1ED'
                }
            },
            opposite: true
        }, {
            gridLineWidth: 0,
            title: {
                text: 'Barometric Pressure',
                style: {
                    color: '#499999'
                }
            },
            labels: {
                format: '{value} kPa',
                style: {
                    color: '#499999'
                }
            },
            opposite: true
        }, {
            gridLineWidth: 0,
            title: 'Wind Speed',
            style: {
                color: '#7EbA86'
            },
            labels: {
                format: '{value} kmh',
                style: {
                    color: '#7EbA86'
                }
            },
            opposite: true
        }, {
            gridLineWidth: 0,
            title: 'Relative Humidity',
            style: {
                color: '#B79468'
            },
            labels: {
                format: '{value} %',
                style: {
                    color: '#B79468'
                }
            }
        }],

        legend: {
            layout:             'vertical',
            align:              'left',
            verticalAlign:      'top',
            x:                  80,
            y:                  20,
            borderWidth:        1,
            floating:           true,
            backgroundColor:    '#FFFFFF'
        },

        credits: {
            href:           'http://monasheemountainmultimedia.com',
            text:           'MMM'
        },

        series: []
    };

    // get the last week data
    var lastweek = moment().subtract('days', 6);
    var rightnow = moment();

    $.post('/getcustomdates', { startDate: lastweek.format('YYYYMMDDHHmmss'), endDate: rightnow.format('YYYYMMDDHHmmss')},
        function(data) {
            options.series = data;
            var chart = new Highcharts.Chart(options);
        }, "json");

    // History date range selector
    $('input[id="daterange"]').daterangepicker({
            format: 'YYYY-MM-DD',
            minDate: '2013-08-23 01:00:02',
            maxDate: moment(),
            timePicker: true,
            ranges: {
                'Last 24 Hours': [moment().subtract('days', 1), moment()],
                'Last 7 Days': [moment().subtract('days', 6), moment()],
                'Last 30 Days': [moment().subtract('days', 29), moment()]
            },
            startDate: '2013-08-23 01:30:00',
            endDate: moment()
        },

        function(start, end) {
            //console.log('Date range: ' + start.format('YYYYMMDDhhmmss') + ' to ' + end.format('YYYYMMDDHHms'));
            // send request to render new chart
            $.post('/getcustomdates', { startDate: start.format('YYYYMMDDHHmmss'), endDate: end.format('YYYYMMDDHHmmss')},
                function(data) {
                    options.series = data;
                    var chart = new Highcharts.Chart(options);
                }, "json");
        }
    );

});