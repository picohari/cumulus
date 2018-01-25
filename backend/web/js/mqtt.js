/*
 * Javascript functions for the backend
 *
 * @author Harald Leschner <leschner@intewa.de>
 * @copyright Copyright (c) 2011-2018, INTEWA GmbH
 * @license https://www.gnu.org/licenses/gpl-3.0.txt GNU General Public License version 3
 */
/*
 * This file is part of INTEWA Cumulus
 *
 * INTEWA Cumulus is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or any later version.
 *
 * INTEWA Cumulus is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * INTEWA Cumulus. If not, see <http://www.gnu.org/licenses/>.
 */



var livingTemp = new Array();
var basementTemp = new Array();



var host = server;
var port = 15675;
var topic = uuid + '/#';
var useTLS = false;
var cleansession = true;
var mqtt;
var reconnectTimeout = 2000;


var offlineTimer;


/* Initiate MQTT connection to RabbitMQ server */
function MQTTconnect() {
    if (typeof path == "undefined") {
        path = '/ws';
    }
    mqtt = new Paho.MQTT.Client(
            host,
            port,
            path,
            "AL-MS-CU_" + Math.floor((1 + Math.random()) * 0x100000).toString(16)	// Generates random client identifier
    );
    var options = {
        userName : "device",    // Fixme: Diese Option auch über YII übergeben
        password : "device",    // Fixme: PW verschlüsseln ?
        mqttVersion: 3,
        timeout: 3,
        useSSL: useTLS,
        cleanSession: cleansession,
        onSuccess: onConnect,
        onFailure: function (message) {
            $('#status').html("Connection failed: " + message.errorMessage + "Retrying...");
            setTimeout(MQTTconnect, reconnectTimeout);
        }
    };

    mqtt.onConnectionLost = onConnectionLost;
    mqtt.onMessageArrived = onMessageArrived;
    console.log("Host: "+ host + ", Port: " + port + ", Path: " + path + " TLS: " + useTLS);
    console.log("Topic: "+ topic);
    mqtt.connect(options);
};

function onConnect() {
    $('#status').html('Connected to ' + host + ':' + port + path);
    mqtt.subscribe(topic, {qos: 0});
    $('#topic').html(topic);
};

function onConnectionLost(response) {
    setTimeout(MQTTconnect, reconnectTimeout);
    //$('#status').html("Connection lost: " + response.errorMessage + ". Reconnecting...");
    console.log("Connection lost: " + response.errorMessage + ". Reconnecting...");

};

function checkDeviceOnline() {
    $('#online_state').text('Offline');
    $('#online_state').removeClass('badge btn-success').addClass('badge btn-danger');

    clearInterval(offlineTimer);
};

function onMessageArrived(message) {
    var topic = message.destinationName;
    var payload = message.payloadString;
    //console.log("Topic: " + topic + ", Message payload: " + payload);
    $('#message').html(topic + ', ' + payload);
    var message = topic.split('/');
    var area = message[1];
    var state = message[2];

    var timestamp = Math.round((new Date()).getTime() / 1000);

    $('#message-ts').html(Date());

    switch (area) {

        case 'online_status':
            if (payload == 'online') {
                $('#online_state').text('Online');
                $('#online_state').removeClass('badge btn-danger').addClass('badge btn-success');

                clearInterval(offlineTimer);
                offlineTimer = setInterval(function () {
                    //console.log("Offline ?");
                    checkDeviceOnline();
                }, 5000);

                //$('#online_state').removeClass('label-danger').addClass('label-success');
            } else {
                $('#online_state').removeClass('badge btn-success').addClass('badge btn-danger');

            }
            break;

        case 'front': 
            $('#value1').html('(Switch value: ' + payload + ')');
            if (payload == 'true') {
                $('#label1').text('Closed');
                $('#label1').removeClass('label-success').addClass('label-success');
            } else {
                $('#label1').text('Open');
                $('#label1').removeClass('label-success').addClass('label-danger');
            }
            break;

        case 'back':
            $('#value2').html('(Switch value: ' + payload + ')');
            if (payload == 'true') {
                $('#label2').text('Closed');
                $('#label2').removeClass('label-danger').addClass('label-success');
            } else {
                $('#label2').text('Open');
                $('#label2').removeClass('label-success').addClass('label-danger');
            }
            break;

        case 'kitchen':
            $('#value3').html('(Switch value: ' + payload + ')');
            if (payload == 'true') {
                $('#label3').text('Closed');
                $('#label3').removeClass('label-danger').addClass('label-success');
            } else {
                $('#label3').text('Open');
                $('#label3').removeClass('label-success').addClass('label-danger');
            }
            break;

        case 'living':
            $('#livingTempSensor').html('(Sensor value: ' + payload + ')');
            $('#livingTempLabel').text(payload + ' RPM');
            $('#livingTempLabel').removeClass('').addClass('label-default');

            var entry = new Array();
            entry.push(timestamp);
            entry.push(parseInt(payload));
            livingTemp.push(entry);
            // Show only 20 values
            if (livingTemp.length >= 20) {
                livingTemp.shift()
            }

            var livingTempPlot = $.jqplot ('livingTempChart', [livingTemp], {
                axesDefaults: {
                    labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                    tickOptions: {
                        showMark: false,
                        showGridline: false,
                        show: false,
                        showLabel: false,
                    }
                  },
                grid: {
                    gridLineColor: '#FFFFFF',
                    borderWidth: 0,
                    shadow: false,
                },
                seriesDefaults: {
                    rendererOptions: {
                        smooth: true
                    },
                    showMarker: false,
                    lineWidth: 2,
                  },
                  axes: {
                    xaxis: {
                      renderer:$.jqplot.DateAxisRenderer,
                      tickOptions:{
                        formatString:'%T'
                      },
                      pad: 0
                    },
                    yaxis: {
                    }
                }
            });
            break;

        case 'basement':
            $('#basementTempSensor').html('(Sensor value: ' + payload + ')');
            if (payload >= 25) {
                    $('#basementTempLabel').text(payload + ' °C - too hot');
                    $('#basementTempLabel').removeClass('label-warning label-success label-info label-primary').addClass('label-danger');
            } else if (payload >= 21) {
                    $('#basementTempLabel').text(payload + ' °C - hot');
                    $('#basementTempLabel').removeClass('label-danger label-success label-info label-primary').addClass('label-warning');
            } else if (payload >= 18) {
                    $('#basementTempLabel').text(payload + ' °C - normal');
                    $('#basementTempLabel').removeClass('label-danger label-warning label-info label-primary').addClass('label-success');
            } else if (payload >= 15) {
                    $('#basementTempLabel').text(payload + ' °C - low');
                    $('#basementTempLabel').removeClass('label-danger label-warning label-success label-primary').addClass('label-info');
            } else if (payload <= 12) {
                    $('#basementTempLabel').text(payload + ' °C - too low');
                    $('#basementTempLabel').removeClass('label-danger label-warning label-success label-info').addClass('label-primary');
            basementTemp.push(parseInt(payload));
            if (basementTemp.length >= 20) {
                basementTemp.shift()
            }

            $('.basementTempSparkline').sparkline(basementTemp, {
                type: 'line',
                width: '160',
                height: '40'});
            }
            break;
            
        default: console.log('Error: Data do not match the MQTT topic.'); break;
    }
};

