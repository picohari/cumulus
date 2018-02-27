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


 /**
 * INTEWA Cumulus namespace
 *
 * holds all data, options and functions for the frontend
 * we dont want to pollute the global namespace
 */
var ic = {
	wui: {					// web user interface
		dialogs: {},
		timeout: null
	},
	plot: {},				// flot instance
	options: {},			// options loaded from cookies in options.js
	
	middleware: [],		// array of all known middlewares

	entities: [],			// entity properties + data
	capabilities: {		// debugging and runtime information from middleware
		definitions: {}	// definitions of entities & properties
	},
	metrics: []
};


/**
 * Executed on document loaded complete
 * this is where it all starts...
 */
$(document).ready(function() {

	window.onerror = function(errorMsg, url, lineNumber) {
		ic.wui.dialogs.error('Javascript Runtime Error', errorMsg);
	};

    // set x axis limits _after_ loading options cookie
    ic.options.plot.xaxis.max = new Date().getTime();
    ic.options.plot.xaxis.min = ic.options.plot.xaxis.max - ic.options.interval;

    //console.log(ic.options.plot.xaxis.min);
    //console.log(ic.options.plot.xaxis.max);

    ic.metrics.push('pressure', 'filllevel', 'current', 'temperature');




    //ic.parseUrlParams();

	// initialize user interface (may need to wait for onLoad on Safari)
	ic.wui.init();

/*
	// middleware(s)
	ic.options.middleware.forEach(function(middleware) {
		ic.middleware.push(new Middleware(middleware));
	});
*/

/*
	ic.entities.push(new Entity({
		//middleware: ic.options.middleware[0].url,		// If provided, then it will NOT be overwritten by options settings...
		active: true,
		//type: metric,
		uuid: "1234"
	}));	
*/


	ic.options.plot.axesAssigned = false; // force axis assignment

	ic.capabilities.load().done(function() {


		ic.metrics.forEach(function(metric) {
			ic.entities.push(new Entity({
				//middleware: ic.options.middleware[0].url,		// If provided, then it will NOT be overwritten by options settings...
				active: true,
				type: metric,
				//uuid: "1234"
			}));		
		});


		//ic.entities.loadDetails().always(function() {
		ic.entities.loadMetrics().always(function() {

			ic.entities.showTable();
/*
			ic.entities.loadData().done(function() {
				// vz.wui.resizePlot();
				ic.wui.drawPlot();
			});
*/

		});



	});

});