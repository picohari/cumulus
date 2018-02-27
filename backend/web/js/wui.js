/**
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

ic.wui.init = function() {

	ic.wui.initEvents();
	ic.wui.resizePlot();

	// bind plot actions
	$('#controls button').click(this.handleControls);
	//$('#controls').controlgroup();

	// bind auto refresh selector
	$('#refresh-interval').change(function() {
		ic.wui.setRefreshInterval();
	});

/*
	// auto refresh
	$('#refresh').prop('checked', ic.options.refresh);
	if (ic.options.refresh) {
		ic.wui.tmaxnow = true;
		ic.wui.setTimeout();
	}


	$('#refresh').change(function() {
		ic.options.refresh = $(this).prop('checked');
		if (ic.options.refresh) {
			ic.wui.refresh(); 		// refresh once
			ic.wui.setTimeout();
		} else {
			ic.wui.clearTimeout();
		}
	});
*/
};

/**
 * Bind events to handle plot zooming & panning
 */
ic.wui.initEvents = function() {
	$('#plot')
		.bind("plotselected", function (event, ranges) {
			ic.wui.period = null;
			ic.wui.zoom(ranges.xaxis.from, ranges.xaxis.to);
		})
		/*.bind('plotpan', function (event, plot) {
			var axes = plot.getAxes();
			ic.options.plot.xaxis.min = axes.xaxis.min;
			ic.options.plot.xaxis.max = axes.xaxis.max;
			ic.options.plot.yaxis.min = axes.yaxis.min;
			ic.options.plot.yaxis.max = axes.yaxis.max;
		})
		.bind('mouseup', function(event) {
			ic.entities.loadData().done(ic.wui.drawPlot);
		})
		.bind('plotzoom', function (event, plot) {
			var axes = plot.getAxes();
			ic.options.plot.xaxis.min = axes.xaxis.min;
			ic.options.plot.xaxis.max = axes.xaxis.max;
			ic.options.plot.yaxis.min = axes.yaxis.min;
			ic.options.plot.yaxis.max = axes.yaxis.max;
			ic.entities.loadData().done(ic.wui.drawPlot);
		});*/
		.bind("plothover", function (event, pos, item) {
			// $('#title').html("pos "+pos.x + " - event-data: "+event.data);
			if (!ic.entities || !ic.entities.length)
				return; // no channels -> nothing to do

			ic.wui.latestPosition = pos;
			if (!ic.wui.updateLegendTimeout)
				ic.wui.updateLegendTimeout = setTimeout(ic.wui.updateLegend, 50);

		});
};


/*
 ____  _       _         _                    _             
|  _ \| | ___ | |_    __| |_ __ __ ___      _(_)_ __   __ _ 
| |_) | |/ _ \| __|  / _` | '__/ _` \ \ /\ / / | '_ \ / _` |
|  __/| | (_) | |_  | (_| | | | (_| |\ V  V /| | | | | (_| |
|_|   |_|\___/ \__|  \__,_|_|  \__,_| \_/\_/ |_|_| |_|\__, |
                                                      |___/ 
*/
ic.wui.drawPlot = function() {
	ic.options.interval = ic.options.plot.xaxis.max - ic.options.plot.xaxis.min;
	
	ic.wui.updateTitle();

	// assign entities to axes
	if (ic.options.plot.axesAssigned === false) {
		ic.entities.each(function(entity) {
			entity.assignAxis();
		}, true);

		ic.options.plot.axesAssigned = true;
	}

	// Demo data for testing
/*
	var d1 = [];
	for (var i = 0; i < Math.PI * 2; i += 0.25) {
		d1.push([i, Math.sin(i)]);
	}
*/
	var series = [];
	ic.entities.each(function(entity) {
		if (entity.active && entity.definition && entity.definition.model == 'Volkszaehler\\Model\\Channel' &&
				entity.data && entity.data.tuples && entity.data.tuples.length > 0) {
			var i, maxTuples = 0;

			// work on copy here to be able to redraw
			var tuples = entity.data.tuples.map(function(t) {
				return t.slice(0);
			});

			var style = ic.options.style || entity.style;
			var fillstyle = parseFloat(ic.options.fillstyle || entity.fillstyle);
			var linewidth = parseFloat(ic.options.linewidth || ic.options[entity.uuid == ic.wui.selectedChannel ? 'lineWidthSelected' : 'lineWidthDefault']);

/*
			// mangle data for "steps" curves by shifting one ts left ("step-before")
			if (style == "steps") {
				tuples.unshift([entity.data.from, 1, 1]); // add new first ts
				for (i=0; i<tuples.length-1; i++) {
					tuples[i][1] = tuples[i+1][1];
				}
			}

			// remove number of datapoints from each tuple to avoid flot fill error
			if (fillstyle || entity.gap) {
				for (i=0; i<tuples.length; i++) {
					maxTuples = Math.max(maxTuples, tuples[i][2]);
					delete tuples[i][2];
				}
			}
*/
			var serie = {
				data: tuples,
				label: entity.title,
				color: entity.color,
				title: entity.title,
				unit : entity.definition.unit,
				lines: {
					show:       style == 'lines' || style == 'steps' || style == 'states',
					steps:      style == 'steps' || style == 'states',
					fill:       fillstyle !== undefined ? fillstyle : false,
					lineWidth:  linewidth
				},
				points: {
					show:       style == 'points'
				},
				yaxis: entity.assignedYaxis

			};

/*
			// disable interpolation when data has gaps
			if (entity.gap) {
				var minGapWidth = (entity.data.to - entity.data.from) / tuples.length;
				serie.xGapThresh = Math.max(entity.gap * 1000 * maxTuples, minGapWidth);
				ic.options.plot.xaxis.insertGaps = true;
			}
*/
			series.push(serie);
		}
	}, true);

	if (series.length === 0) {
		$('#overlay').html('<img src="img/empty.png" alt="no data..." /><p>nothing to plot...</p>');
		series.push({}); // add empty dataset to show axes
	}
	else {
		$('#overlay').empty();
	}

	//ic.plot = $.plot($('#flot'), [ { label: "sin(x)", data: d1 } ], ic.options.plot);
	//console.log(series);

	ic.plot = $.plot($('#flot'), series, ic.options.plot);

	// remember legend container for updating
	//ic.wui.legend = $('.legend .legendLabel');
	ic.wui.legend = $('#legend .legendLabel');

	// disable automatic refresh if we are in past
	if (ic.options.refresh) {
		if (ic.wui.tmaxnow) {
			ic.wui.setTimeout();
		} else {
			ic.wui.clearTimeout('(suspended)');
		}
	} else {
		ic.wui.clearTimeout();
	}


};

/**
 * Update title of time-range
 */
ic.wui.updateTitle = function() {
    var delta = ic.options.plot.xaxis.max - ic.options.plot.xaxis.min,
            format = '%a %e. %b %Y',
            from = ic.options.plot.xaxis.min,
            to = ic.options.plot.xaxis.max;

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
        $.plot.dateGenerator(from, ic.options.plot.xaxis),
        format, null, null, true
    );
    to = $.plot.formatDate(
        $.plot.dateGenerator(to, ic.options.plot.xaxis),
        format, null, null, true
    );

    $('#title').html(from + ' - ' + to);
};

/**
 * Update legend on move hover
 */
ic.wui.updateLegend = function() {
	ic.wui.updateLegendTimeout = null;
	var pos = ic.wui.latestPosition;

	var axes = ic.plot.getAxes();
	if (pos.x < axes.xaxis.min || pos.x > axes.xaxis.max ||
		pos.y < axes.yaxis.min || pos.y > axes.yaxis.max)
		return;

	var i, j, dataset = ic.plot.getData();
	for (i = 0; i < dataset.length; ++i) {
		var series = dataset[i];

		if (!series.data.length)
			continue;

		// find the nearest points, x-wise
		for (j = 0; j < series.data.length; ++j)
			if (series.data[j][0] > pos.x)
				break;
		var y;
		if (series.lines.steps) {
			var p = series.data[j-1];
			if (p)
				y = p[1];
			else
				y = null;
		} else if (series.lines.states) {
			y = null;
			if (j < series.data.length) {
				var p3 = series.data[j];
				if (p3)
					y = p3[1];
			}
		} else { // no steps -> interpolate
			var p1 = series.data[j - 1], p2 = series.data[j];
			if (p1 == null || p2 == null) // jshint ignore:line
				y = null;
			else
				y = p1[1] + (p2[1] - p1[1]) * (pos.x - p1[0]) / (p2[0] - p1[0]);
		}

		var legend = $('#legend .legendLabel');
		if (y === null) {
			legend.eq(i).text(series.title);
		} else {
			// use plot wrapper instead of `new Date()` for timezone support
			var d = $.plot.dateGenerator(pos.x, ic.options.plot.xaxis);
			var delta = ic.options.plot.xaxis.max - ic.options.plot.xaxis.min;
			var format = (delta > 1*24*3600*1000) ? '%d.%m.%y - %H:%M' : '%H:%M:%S';
			legend.eq(i).text(series.title + ": " + $.plot.formatDate(d,format) + " - " + ic.wui.formatNumber(y, series.unit));
		}
	}

	// update opaque background sizing
	$('#legend > div').css({ width: $('#legend table').css('width') });
};

/**
 * Zoom plot to target timeframe
 */
ic.wui.zoom = function(from, to) {
	// we cannot zoom/pan into the future
	var now = new Date().getTime();
	if (to > now) {
		var delta = to - from;
		ic.options.plot.xaxis.min = now - delta;
		ic.options.plot.xaxis.max = now;
	} else {
		ic.options.plot.xaxis.min = from;
		ic.options.plot.xaxis.max = to;
	}

	ic.wui.tmaxnow = (ic.options.plot.xaxis.max >= (now - 1000));

	if (ic.options.plot.xaxis.min < 0) {
		ic.options.plot.xaxis.min = 0;
	}

	ic.entities.loadData().done(ic.wui.drawPlot);
};

/**
 * Adjust plot when screen size changes
 */
ic.wui.resizePlot = function(evt, windowHeight) {
	// resize container depending on window vs. content height
	var delta = (windowHeight || $(window).height()) - $('html').height();
	$('#flot').height(Math.max($('#flot').height() + delta, ic.options.plot.minHeight || 300));

	ic.options.tuples = Math.round($('#flot').width() / 3);

	if (ic.plot && ic.plot.resize) {
		ic.plot.resize();
		îc.plot.setupGrid();
		ic.plot.draw();
	}
};


/*
  ____            _             _     
 / ___|___  _ __ | |_ _ __ ___ | |___ 
| |   / _ \| '_ \| __| '__/ _ \| / __|
| |__| (_) | | | | |_| | | (_) | \__ \
 \____\___/|_| |_|\__|_|  \___/|_|___/
*/

/**
 * Move & zoom in the plotting area
 */
ic.wui.handleControls = function () {
	var delta = ic.options.plot.xaxis.max - ic.options.plot.xaxis.min,
			middle = ic.options.plot.xaxis.min + delta/2,
			startOfPeriodLocale;

	var control = $(this).val();
	switch (control) {
		case 'move-last':
			startOfPeriodLocale = ic.wui.period == 'week' ? 'isoweek' : ic.wui.period;
			ic.wui.zoom(
				/* jshint laxbreak: true */
				ic.wui.period && moment(ic.options.plot.xaxis.min)
					.startOf(startOfPeriodLocale)
					.isSame(moment(ic.options.plot.xaxis.min))
					? moment().startOf(startOfPeriodLocale).valueOf()
					: moment().valueOf() - delta,
				moment().valueOf()
			);
			break;
		case 'move-back':
			if (ic.wui.period) {
				ic.wui.zoom(
					moment(ic.options.plot.xaxis.min).subtract(1, ic.wui.period).valueOf(),
					ic.options.plot.xaxis.min
				);
			}
			else {
				ic.wui.zoom(
					ic.options.plot.xaxis.min - delta,
					ic.options.plot.xaxis.max - delta
				);
			}
			break;
		case 'move-forward':
			// don't move into the future
			if (ic.wui.tmaxnow)
				break;
			if (ic.wui.period) {
				ic.wui.zoom(
					ic.options.plot.xaxis.max,
					Math.min(
						// prevent adjusting left boundary in zoom function
						moment(ic.options.plot.xaxis.max).add(1, ic.wui.period).valueOf(),
						moment().valueOf()
					)
				);
			}
			else {
				ic.wui.zoom(
					ic.options.plot.xaxis.min + delta,
					ic.options.plot.xaxis.max + delta
				);
			}
			break;
		case 'zoom-reset':
			ic.wui.period = null;
			ic.wui.zoom(
				middle - ic.options.defaultInterval/2,
				middle + ic.options.defaultInterval/2
			);
			break;
		case 'zoom-in':
			ic.wui.period = null;
			if (ic.wui.tmaxnow)
				ic.wui.zoom(moment().valueOf() - delta/2, moment().valueOf());
			else
				ic.wui.zoom(middle - delta/4, middle + delta/4);
			break;
		case 'zoom-out':
			ic.wui.period = null;
			ic.wui.zoom(
				middle - delta,
				middle + delta
			);
			break;
		case 'zoom-hour':
		case 'zoom-day':
		case 'zoom-week':
		case 'zoom-month':
		case 'zoom-year':
			var period = control.split('-')[1], min, max;
			startOfPeriodLocale = period == 'week' ? 'isoweek' : period;

			if (ic.wui.tmaxnow) {
				/* jshint laxbreak: true */
				min = period === ic.wui.period
					? moment().subtract(1, period).valueOf()
					: moment().startOf(startOfPeriodLocale).valueOf();
				max = moment().valueOf();
			}
			else {
				min = moment(middle).startOf(startOfPeriodLocale).valueOf();
				max = moment(middle).startOf(startOfPeriodLocale).add(1, period).valueOf();
			}

			ic.wui.period = period;
			ic.wui.zoom(min, Math.min(max, moment().valueOf()));
			break;
	}
};


/*
 _____                          _   _   _             
|  ___|__  _ __ _ __ ___   __ _| |_| |_(_)_ __   __ _ 
| |_ / _ \| '__| '_ ` _ \ / _` | __| __| | '_ \ / _` |
|  _| (_) | |  | | | | | | (_| | |_| |_| | | | | (_| |
|_|  \___/|_|  |_| |_| |_|\__,_|\__|\__|_|_| |_|\__, |
                                                |___/ 
*/
/**
 * Flot tickFormatter extension to apply axis labels
 * Copied from jquery.flot.js
 */
ic.wui.tickFormatter = function (value, axis, tickIndex, ticks) {
	
	// return label instead of last tick
	if (ticks && tickIndex === ticks.length-1 && axis.options.axisLabel) {
		console.log("Y-Axis");
		return '[' + axis.options.axisLabel + ']';
	}

	var factor = axis.tickDecimals ? Math.pow(10, axis.tickDecimals) : 1;
	var formatted = "" + Math.round(value * factor) / factor;

	if (axis.tickDecimals !== null) {
		var decimal = formatted.indexOf(".");
		var precision = decimal == -1 ? 0 : formatted.length - decimal - 1;
		if (precision < axis.tickDecimals) {
			return (precision ? formatted : formatted + ".") + ("" + factor).substr(1, axis.tickDecimals - precision);
		}
	}

	return formatted;
};

/**
 * Rounding precision
 *
 * Math.round rounds to whole numbers
 * to round to one decimal (e.g. 15.2) we multiply by 10,
 * round and reverse the multiplication again
 * therefore "ic.options.precision" needs
 * to be set to 1 (for 1 decimal) in that case
 */
ic.wui.formatNumber = function(number, unit, prefix) {
	prefix = prefix || true; // default on
	var siPrefixes = ['k', 'M', 'G', 'T'];
	var siIndex = 0,
			maxIndex = (typeof prefix == 'string') ? siPrefixes.indexOf(prefix)+1 : siPrefixes.length;

	// flow unit or air pressure?
	if (['l', 'm3', 'm^3', 'm³', 'l/h', 'm3/h', 'm/h^3', 'm³/h', 'hPa'].indexOf(unit) >= 0) {
		// don't scale...
		maxIndex = -1;

		// ...unless for l->m3 conversion
		if (Math.abs(number) > 1000 && (unit == 'l' || unit == 'l/h')) {
			unit = 'm³' + unit.substring(1);
			number /= 1000;
		}
	}

	while (prefix && Math.abs(number) > 1000 && siIndex < maxIndex) {
		number /= 1000;
		siIndex++;
	}

	// avoid infinities/NaN
	if (number < 0 || number > 0) {
		var precision = Math.max(0, ic.options.precision - Math.floor(Math.log(Math.abs(number))/Math.LN10));
		// apply maximum precision e.g. for °C values
		if (ic.options.maxPrecision[unit] !== undefined) {
			precision = Math.min(ic.options.maxPrecision[unit], precision);
		}
		number = Math.round(number * Math.pow(10, precision)) / Math.pow(10, precision); // rounding
	}

	// avoid almost zero
	if (Math.abs(number) < Math.pow(10, -ic.options.precision)) {
		number = 0;
	}

	if (prefix)
		number += (siIndex > 0) ? ' ' + siPrefixes[siIndex-1] : ' ';
	else
		number += ' ';

	if (unit) number += unit;

	return number;
};


/*
    _         _              ____       __               _     
   / \  _   _| |_ ___       |  _ \ ___ / _|_ __ ___  ___| |__  
  / _ \| | | | __/ _ \ _____| |_) / _ \ |_| '__/ _ \/ __| '_ \ 
 / ___ \ |_| | || (_) |_____|  _ <  __/  _| | |  __/\__ \ | | |
/_/   \_\__,_|\__\___/      |_| \_\___|_| |_|  \___||___/_| |_|
*/

/**
 * Refresh plot with new data
 */
ic.wui.refresh = function() {
	var delta = ic.options.plot.xaxis.max - ic.options.plot.xaxis.min;
	ic.wui.zoom( // move plot
		new Date().getTime() - delta,
		new Date().getTime()
	);
};

ic.wui.setRefreshInterval = function() {

	ic.wui.timeout = $("#refresh-interval").val();

	if (ic.wui.timeout == "undefined") {
		window.clearInterval(ic.wui.timer);
	} else {
		window.clearInterval(ic.wui.timer);
		ic.wui.timer = window.setInterval(ic.wui.refresh, ic.wui.timeout * 1000);
	}

};

/**
 * Refresh graphs after timeout ms, with a minimum of ic.options.minTimeout ms
 */
ic.wui.setTimeout = function() {
	// clear an already set timeout
	if (ic.wui.timeout !== null) {
		window.clearTimeout(ic.wui.timeout);
		ic.wui.timeout = null;
	}

	var t = Math.max((ic.options.plot.xaxis.max - ic.options.plot.xaxis.min) / ic.options.tuples, ic.options.minTimeout);
	ic.wui.timeout = window.setTimeout(ic.wui.refresh, t);

	$('#refresh-time').html('(' + Math.round(t / 1000) + ' s)');
};

/**
 * Stop auto-refresh of graphs
 */
ic.wui.clearTimeout = function(text) {
	$('#refresh-time').html(text || '');

	var rc = window.clearTimeout(ic.wui.timeout);
	ic.wui.timeout = null;
	return rc;
};


/*
 _____                     _                     _ _ _             
| ____|_ __ _ __ ___  _ __| |__   __ _ _ __   __| | (_)_ __   __ _ 
|  _| | '__| '__/ _ \| '__| '_ \ / _` | '_ \ / _` | | | '_ \ / _` |
| |___| |  | | | (_) | |  | | | | (_| | | | | (_| | | | | | | (_| |
|_____|_|  |_|  \___/|_|  |_| |_|\__,_|_| |_|\__,_|_|_|_| |_|\__, |
                                                             |___/ 
*/

ic.wui.dialogs.error = function(error, description, code) {
	if (code) {
		error = code + ': ' + error;
	}

	// make error messages singleton (suppress follow-on errors)
	ic.wui.errorDialog = true;

	$('<div>').append(
		$('<span>').html(description)
	).dialog({
		title: error,
		width: 450,
		dialogClass: 'ui-error',
		resizable: false,
		modal: true,
		close: function() {
			ic.wui.errorDialog = false;
		},
		buttons: {
			Ok: function() {
				$(this).dialog('close');
			}
		},
/*
		open: function() {
			$(this).next().find('button:eq(0)').focus();
		}
*/
		// FIXME: Close icon not showing:
		// SEE:   https://stackoverflow.com/questions/17367736/jquery-ui-dialog-missing-close-icon
	    open: function() {
	        $(this).closest(".ui-dialog")
	        .find(".ui-dialog-titlebar-close")
	        .removeClass("ui-dialog-titlebar-close")
	        .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
	    }
	});
};

ic.wui.dialogs.exception = function(exception) {
	if (ic.wui.errorDialog) return; // only one error dialog at a time
	this.error(exception.type, exception.message, exception.code);
};

ic.wui.dialogs.middlewareException = function(xhr) {
	if (xhr.responseJSON && xhr.responseJSON.exception)
		// middleware exception
		this.exception(new Exception(xhr.responseJSON.exception.type, "<a href='" + xhr.requestUrl + "' style='text-decoration:none'>" + xhr.requestUrl + "</a>:<br/><br/>" + xhr.responseJSON.exception.message));
	else
		// network exception
		this.exception(new Exception("Network Error", xhr.statusText));
};
