var jsonurl = 'http://localhost/~dk6yf/sapiroid/yii2/backend/web/index.php?r=timeseries/getdata';
var plot;


var ic = {
    plot: { },              // flot instance
    options: { },           // options loaded from cookies in options.js
    wui: {                      // web user interface
        dialogs: { },
        timeout: null
    },
};


var plotdata = {

    device_type: "AQUALOOP",

    url: 'http://localhost/~dk6yf/sapiroid/yii2/backend/web/index.php?r=timeseries/getdata',
    metrics: ['temperature', 'pressure', 'tanklevel'],

    /*
    fullName: function() {
       return this.firstName + " " + this.lastName;
    }
    */
}





ic.options.plot = {
    colors: ['#83CAFF', '#7E0021', '#FFD320', '#FF420E', '#004586', '#0084D1', '#C5000B', '#FF950E', '#4B1F6F', '#AECF00', '#314004', '#83CAFF'],
    series: {
        lines: {
            show: true,
            lineWidth: 0.9,
        },
        points: {
            show: false,
        },
        shadowSize: 0.95,
        //curvedLines: {active: true},
    },
    crosshair: {
        mode: "x",
    },
    selection: {
        mode: "x",
    },
    grid: {
        hoverable: true,
        autoHighlight: false,
        borderWidth: 0,
        borderColor: '#FFFFFF',
    },
    xaxis: { 
        mode: "time",
        timeformat: "%d.%m.%Y <br/> %H:%M:%S",
    },
};


function fetchAjaxData(url, success) {

    var tuples = [[]];
    var ret;

    $.ajax({
        url: url,
        dataType:"json",
        success: function(data) {

            ret = data.data.tuples;

            var len = Object.keys(ret).length;

            for (var i = 0; i < len; i++) {
                tuples[0].push([ret[i][0] * 1000, ret[i][1]]);
            }

            success(tuples);

            console.log('Loaded %d JSON tuples', len);
        }
    });
};


function drawPlot(from, to) {

/*
    for (var i = 0; i < 14; i += 0.1) {
        sin.push([i, Math.sin(i)]);
        cos.push([i, Math.cos(i)]);
    }
*/
    fetchAjaxData(jsonurl  + "&from=" + from + "&to=" + to + "&device_id=" + device_id + "&metric=temperature", function(data) {
        if (plot == null) {

            /* jQplot Version */
            console.log(data);
            
            plot = $.plot($("#flot"), data, ic.options.plot);

        } else {
            plot.replot({data: data});
            console.log('replotting');
        }

    });

};




/**
 * Executed on document loaded complete
 * this is where it all starts...
 */
$(document).ready(function() {

    ic.wui.resizePlot();

    drawPlot(0, 'now');

});














/**
 * Adjust plot when screen size changes
 */
ic.wui.resizePlot = function(evt, windowHeight) {
    // resize container depending on window vs. content height
    var delta = (windowHeight || $(window).height()) - $('html').height();
    $('#flot').height(Math.max($('#flot').height() + delta, ic.options.plot.minHeight || 300));

    //ic.options.tuples = Math.round($('#flot').width() / 3);

    if (ic.plot && ic.plot.resize) {
        ic.plot.resize();
        îc.plot.setupGrid();
        ic.plot.draw();
    }
};


