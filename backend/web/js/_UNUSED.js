

















































function fetchAjaxData(url, success) {

    var tuples = [[]];
    var ret;

    $.ajax({
        url: url,
        dataType:"json",
        success: function(data) {

            ret = data.data;

            var len = Object.keys(ret).length;

            for (var i = 0; i < len; i++) {
                tuples[0].push([ret[i][0] * 1000, ret[i][1]]);
            }

            success(tuples);
            console.log('Loaded %d JSON tuples', len);
        }
    });
};



var sin = [], cos = [];

function PlotData(from, to) {

    for (var i = 0; i < 14; i += 0.1) {
        sin.push([i, Math.sin(i)]);
        cos.push([i, Math.cos(i)]);
    }

    fetchAjaxData(jsonurl  + "&from=" + from + "&to=" + to, function(data) {
        if (plot == null) {

            /* jQplot Version */
            plot = $.plot($("#placeholder"),

                    data,
                    
                    //[{ data: sin, label: "sin(x) = -0.00"},
                    //{ data: cos,  label: "cos(x) = -0.00" }],

                	al.options.plot
/*
                {
                series: {
                    lines: {
                        show: true,
                        lineWidth: 0.9
                    },
                    points: {
                        show: false
                    },
                    shadowSize: 0.95
                },

                crosshair: {
                    mode: "x"
                },

                selection: {
                    mode: "x"
                },

                grid: {
                    hoverable: true,
                    autoHighlight: false,
                    borderWidth: 0,
                    borderColor: '#FFFFFF',
                },

                xaxis: { 
                    mode: "time",
                    timeformat: "%d.%m.%Y <br/> %H:%M:%S"
                },

            }
*/
            );

        } else {
            plot.replot({data: data});
            console.log('replotting');
        }

    });

};



var legends = $("#placeholder .legendLabel");

var updateLegendTimeout = null;
var latestPosition = null;

function updateLegend() {

    updateLegendTimeout = null;

    legends = $("#placeholder .legendLabel");

    var pos = latestPosition;

    var axes = plot.getAxes();
    if (pos.x < axes.xaxis.min || pos.x > axes.xaxis.max ||
        pos.y < axes.yaxis.min || pos.y > axes.yaxis.max) {
        return;
    }

    var i, j, dataset = plot.getData();

    for (i = 0; i < dataset.length; ++i) {

        var series = dataset[i];

        // Find the nearest points, x-wise

        for (j = 0; j < series.data.length; ++j) {
            if (series.data[j][0] > pos.x) {
                break;
            }
        }

        // Now Interpolate

        var y,
            p1 = series.data[j - 1],
            p2 = series.data[j];

        if (p1 == null) {
            y = p2[1];
        } else if (p2 == null) {
            y = p1[1];
        } else {
            y = p1[1] + (p2[1] - p1[1]) * (pos.x - p1[0]) / (p2[0] - p1[0]);
        }

        legends.eq(i).text(series.label.replace(/=.*/, "= " + y.toFixed(2)));
    }
};


function updateHeadline() {
    var delta = al.options.plot.xaxis.max - al.options.plot.xaxis.min,
            format = '%a %e. %b %Y',
            from = al.options.plot.xaxis.min,
            to = al.options.plot.xaxis.max;

    if (delta < 3*24*3600*1000) {
        format += ' %H:%M'; // under 3 days
        if (delta < 5*60*1000) format += ':%S'; // under 5 minutes
    }
    else {
        // formatting days only- remove 1ms to display previous day as "to"
        to--;
    }

    // timezone-aware dates if timezone-js is included
    from = $.plot.formatDate(
        $.plot.dateGenerator(from, al.options.plot.xaxis),
        format, null, null, true
    );
    to = $.plot.formatDate(
        $.plot.dateGenerator(to, al.options.plot.xaxis),
        format, null, null, true
    );

    $('#title').html(from + ' - ' + to);
};


/*


    $("#placeholder").resize(function() {
        //console.log("Resize!");
        //plot1.replot( { resetAxes: true } );
        plot.setupGrid(); //only necessary if your new data will change the axes or grid
        plot.draw();
    });
























        //DrawPlot(jsonurl);

        // set x axis limits _after_ loading options cookie
        al.options.plot.xaxis.max = new Date().getTime();
        al.options.plot.xaxis.min = al.options.plot.xaxis.max - al.options.interval;

        console.log(al.options.plot.xaxis.min);
        console.log(al.options.plot.xaxis.max);

        updateHeadline();

        PlotData(0, 'now');

        // Check device is online every 5 sec
        offlineTimer = setInterval(function () {
            //console.log("Offline ?");
            checkDeviceOnline();
        }, 5000);  

        // Fix the legend widths so they don't jump around
        $("#placeholder .legendLabel").each(function () {
            $(this).css('width', $(this).width());
        });



        $("#placeholder").bind("plothover", function (event, pos, item) {
            //console.log("Hovering!");
            latestPosition = pos;
            if (!updateLegendTimeout) {
                //updateLegendTimeout = setTimeout(updateLegend, 100);
            }
        });

        
        $("#placeholder").bind("plotselected", function (event, ranges) {

            //$("#selection").text(ranges.xaxis.from.toFixed(1) + " to " + ranges.xaxis.to.toFixed(1));

            //var zoom = $("#zoom").prop("checked");

            console.log("Zoom!");

            $.each(plot.getXAxes(), function(_, axis) {
                var opts = axis.options;
                opts.min = ranges.xaxis.from;
                opts.max = ranges.xaxis.to;
            });


            plot.setupGrid();
            plot.draw();
            plot.clearSelection();

        });


*/













































    PlotData(0, 'now');

    // Check device is online every 5 sec
    offlineTimer = setInterval(function () {
        //console.log("Offline ?");
        checkDeviceOnline();
    }, 5000);  


    // Fix the legend widths so they don't jump around
    $("#placeholder .legendLabel").each(function () {
        $(this).css('width', $(this).width());
    });



    $("#placeholder").bind("plothover", function (event, pos, item) {
        //console.log("Hovering!");
        latestPosition = pos;
        if (!updateLegendTimeout) {
            //updateLegendTimeout = setTimeout(updateLegend, 100);
        }
    });

    
    $("#placeholder").bind("plotselected", function (event, ranges) {

        //$("#selection").text(ranges.xaxis.from.toFixed(1) + " to " + ranges.xaxis.to.toFixed(1));

        //var zoom = $("#zoom").prop("checked");

        //console.log("Zoom!");

        $.each(plot.getXAxes(), function(_, axis) {
            var opts = axis.options;
            opts.min = ranges.xaxis.from;
            opts.max = ranges.xaxis.to;
        });


        plot.setupGrid();
        plot.draw();
        plot.clearSelection();

    });