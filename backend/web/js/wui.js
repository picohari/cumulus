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

};












/**
 * Bind events to handle plot zooming & panning
 */
ic.wui.initEvents = function() {
	$('#plot')
		.bind("plotselected", function (event, ranges) {
			ic.wui.period = null;
			//ic.wui.zoom(ranges.xaxis.from, ranges.xaxis.to);
		})
		/*.bind('plotpan', function (event, plot) {
			var axes = plot.getAxes();
			vz.options.plot.xaxis.min = axes.xaxis.min;
			vz.options.plot.xaxis.max = axes.xaxis.max;
			vz.options.plot.yaxis.min = axes.yaxis.min;
			vz.options.plot.yaxis.max = axes.yaxis.max;
		})
		.bind('mouseup', function(event) {
			vz.entities.loadData().done(vz.wui.drawPlot);
		})
		.bind('plotzoom', function (event, plot) {
			var axes = plot.getAxes();
			vz.options.plot.xaxis.min = axes.xaxis.min;
			vz.options.plot.xaxis.max = axes.xaxis.max;
			vz.options.plot.yaxis.min = axes.yaxis.min;
			vz.options.plot.yaxis.max = axes.yaxis.max;
			vz.entities.loadData().done(vz.wui.drawPlot);
		});*/
		.bind("plothover", function (event, pos, item) {
			// $('#title').html("pos "+pos.x + " - event-data: "+event.data);
			if (!al.entities || !al.entities.length)
				return; // no channels -> nothing to do
			/*
			ic.wui.latestPosition = pos;
			if (!ic.wui.updateLegendTimeout)
				ic.wui.updateLegendTimeout = setTimeout(ic.wui.updateLegend, 50);
			*/
		});
};


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






ic.wui.drawPlot = function() {

}


















/*
 * Error & Exception handling
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
