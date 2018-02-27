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


ic.options = {
    language: 'en',
    precision: 2,                   // TODO update from middleware capabilities?
    maxPrecision: {                 // override precision for certain units
        '°C': 1
    },
    tuples: null,               	// automatically determined by plot size
    refresh: false,              	// update chart if zoomed to current timestamp
    interval: 1*24*60*60*1000,    	// 1 day default time interval to show
    minTimeout: 2000,
    lineWidthDefault: 0.8,
    lineWidthSelected: 1,
	middleware: [
		{
			title: 'Local (default)',
            //url: 'http://localhost/~dk6yf/sapiroid/yii2/backend/web/index.php?r=timeseries/getdata'
			url: 'http://localhost/~dk6yf/sapiroid/yii2/backend/web'
		},
	],
};


/**
 * Plot options are passed on to flot
 */
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
        timezone: 'browser',
    },
    yaxis: {
        //min: 40,
        //max: 80,
        //tickSize: 5,
    },
    yaxes: [
        {
            axisLabel: '', // assign el. energy to first axis- remove if not used
            tickFormatter: ic.wui.tickFormatter     // show axis label
        },
        {
            //alignTicksWithAxis: 1,
            position: 'top',
            tickFormatter: ic.wui.tickFormatter     // show axis label
        }
    ],
    legend: {
        show: true,
        position: 'nw',
        backgroundOpacity: 0.80,
        container: '#legend'
    },
    axisLabels: {
        show: true // set to true to show labels
    },
/*
    series: {
        shadowSize: 0,
        lines: {
            show: true,
            lineWidth: 0.9
        },
        points: {
            radius: 3
        }
    },
    xaxis: {
        mode: 'time',
        timezone: 'browser'
    },
    selection: { mode: 'x' },
    crosshair: { mode: 'x' },
    grid: {
        hoverable: true,
        autoHighlight: false
    }
*/
};
