

/**
 * Entity constructor
 * @var data object properties etc.
 * @var middleware url (if not passed as data attribute)
 */
var Entity = function(data, middleware) {

	this.parseJSON($.extend({
		middleware: middleware
	}, data));

};

/**
 * Query middleware for details about entity 
 * @return jQuery dereferred object
 */
Entity.prototype.loadMetrics = function(skipDefaultErrorHandling) {

	console.log("Metrics..");

	delete this.children; // clear children first

	return ic.load({
		url: this.middleware,
		controller: 'timeseries/getmetrics&device_id=1',
		identifier: this.type,
		context: this
	}, skipDefaultErrorHandling).done(function(json) {
		// fix https://github.com/volkszaehler/volkszaehler.org/pull/560
		delete json.entity.active;
		
		this.parseJSON(json.entity);

		this.eachChild(function(child) {
			child.active = true;
		}, true); // recursive

	});
};

/**
 * Query middleware for details about entity 
 * @return jQuery dereferred object
 */
Entity.prototype.loadDetails = function(skipDefaultErrorHandling) {

	console.log("Details..");

	delete this.children; // clear children first

	return ic.load({
		url: this.middleware,
		controller: 'timeseries/getmetric&device_id=1',
		identifier: this.uuid,
		context: this
	}, skipDefaultErrorHandling).done(function(json) {
		// fix https://github.com/volkszaehler/volkszaehler.org/pull/560
		delete json.entity.active;
		
		this.parseJSON(json.entity);

		this.eachChild(function(child) {
			child.active = true;
		}, true); // recursive

	});
};

/**
 * Fetch entity data from middleware
 * @return jQuery dereferred object
 */
Entity.prototype.loadData = function (metric) {

	if (!this.hasData()) {
		return $.Deferred().resolve().promise();
	}

	return ic.load({
		controller: 'timeseries/getdata&device_id=1&metric=' + metric,
		url: this.middleware,
		identifier: this.uuid,
		context: this,
		data: {
			from: Math.floor(ic.options.plot.xaxis.min/1000),
			to: Math.ceil(ic.options.plot.xaxis.max/1000),
			tuples: ic.options.group === undefined ? ic.options.tuples : Number.MAX_SAFE_INTEGER,
			//group: ic.entities.speedupFactor()
		}
	}).done(function(json) {
		this.data = json.data;
		this.dataUpdated();
	});
};	

/**
 * Parse middleware response (recursive creation of children etc)
 * @var json object from middleware response
 */
Entity.prototype.parseJSON = function(json) {
	$.extend(true, this, json);

	// force axis assignment before plotting
	ic.options.plot.axesAssigned = false;

	// parse children
	if (this.children) {
		for (var i = 0; i < this.children.length; i++) {
			// ensure middleware gets inherited
			this.children[i] = new Entity(this.children[i], this.middleware);
			this.children[i].parent = this;
		}

		this.children.sort(Entity.compare);
	}


	// setting defaults
	if (this.type !== undefined) {
		this.definition = ic.capabilities.definitions.get('entities', this.type);

		if (this.style === undefined) {
			if (this.definition.style) {
				this.style = this.definition.style;
			}
			else {
				this.style = (this.definition.interpreter == 'Volkszaehler\\Interpreter\\SensorInterpreter') ? 'lines' : 'steps';
			}
		}
	}


	if (this.active === undefined || this.active === null) {
		this.active = true; // activate by default
	}

	if (this.color === undefined) {
		this.color = ic.options.plot.colors[Entity.colors++ % ic.options.plot.colors.length];
	}

	// store json data to be extensible by push updates
	if (this.data === undefined) {
		this.data = {
			tuples: [],
			// min, max remain undefined
		};
	}
};



/**
 * Update UI when data changes
 */
Entity.prototype.dataUpdated = function(data) {
	this.updateAxisScale();
	this.updateDOMRow();
};

/**
 * Check if data can be loaded from entity
 */
Entity.prototype.hasData = function() {
	return this.active && this.definition && this.definition.model == 'Volkszaehler\\Model\\Channel';
};

/**
 * Calls the callback function for the entity and all nested children
 *
 * @param cb callback function
 */
Entity.prototype.eachChild = function(cb, recursive) {
	if (this.children) {
		for (var i = 0; i < this.children.length; i++) {
			cb(this.children[i], this);

			if (recursive && this.children[i].children) {
				this.children[i].eachChild(cb, true); // call recursive
			}
		}
	}
};

/**
 * Compares two entities for sorting
 *
 * @static
 * @todo Channels before Aggregators
 */
Entity.compare = function(a, b) {
	if (a.definition === undefined)
		return -1;
	if (b.definition === undefined)
		return 1;
	// Channels before Aggregators
	if (a.definition.model == 'Volkszaehler\\Model\\Channel' && b.definition.model == 'Volkszaehler\\Model\\Aggregator')
		return -1;
	else if (a.definition.model == 'Volkszaehler\\Model\\Aggregator' && b.definition.model == 'Volkszaehler\\Model\\Channel')
		return 1;
	else
		return ((a.title < b.title) ? -1 : ((a.title > b.title) ? 1 : 0));
};















/**
 * Get entity unit
 */
Entity.prototype.getUnit = function() {
	return this.definition.unit || this.unit || "";
};

/**
 * Assign entity an axis with matching unit
 */
Entity.prototype.assignMatchingAxis = function() {
	if (this.definition) {
		// find axis with matching unit
		if (ic.options.plot.yaxes.some(function(yaxis, idx) {
			if (yaxis.axisLabel === undefined || (this.getUnit() == yaxis.axisLabel)) { // unoccupied or matching unit
				this.assignedYaxis = idx + 1;
				return true;
			}
		}, this) === false) { // no more axes available
			this.assignedYaxis = ic.options.plot.yaxes.push($.extend({}, ic.options.plot.yaxes[1]));
		}

		ic.options.plot.yaxes[this.assignedYaxis-1].axisLabel = this.getUnit();
	}
};

/**
 * Allocate y-axis for entity
 */
Entity.prototype.assignAxis = function() {
	// assign y-axis
	if (this.yaxis === undefined || this.yaxis == 'auto') { // auto axis assignment
		this.assignMatchingAxis();
	}
	else { // forced axis assignment
		this.assignedYaxis = parseInt(this.yaxis); // string to int for multi-property

		while (ic.options.plot.yaxes.length < this.assignedYaxis) { // no more axes available
			// create new right-hand axis
			ic.options.plot.yaxes.push($.extend({}, ic.options.plot.yaxes[1]));
		}

		// check if axis already has auto-allocated entities
		var yaxis = ic.options.plot.yaxes[this.assignedYaxis-1];
		if (yaxis.forcedGroup === undefined) { // axis auto-assigned
			if (yaxis.axisLabel !== undefined && this.getUnit() !== yaxis.axisLabel) { // unit mismatch
				// move previously auto-assigned entities to different axis
				yaxis.axisLabel = '*'; // force unit mismatch
				ic.entities.each((function(entity) {
					if (entity.assignedYaxis == this.yaxis && (entity.yaxis === undefined || entity.yaxis == 'auto')) {
						entity.assignMatchingAxis();
					}
				}).bind(this), true); // bind to have callback->this = this
			}
		}

		yaxis.axisLabel = this.getUnit();
		yaxis.forcedGroup = this.yaxis;
	}

	this.updateAxisScale();
};

/**
 * Set axis minimum depending on data
 *
 * Note: axis.min can have the following values:
 *         - undefined: not initialized yet, will only happen during assignment of first entity to axis
 *         - null:      min value intentionally set to 'auto' to allow negative values
 *         - 0:         min value assumed to be '0' as long as no entity with negative values is encountered
 */
Entity.prototype.updateAxisScale = function() {
	if (this.assignedYaxis !== undefined && ic.options.plot.yaxes.length >= this.assignedYaxis) {
		if (ic.options.plot.yaxes[this.assignedYaxis-1].min === undefined) { // axis min still not set
			// avoid overriding user-defined options
			ic.options.plot.yaxes[this.assignedYaxis-1].min = 0;
		}
		if (this.data && this.data.tuples && this.data.tuples.length > 0) {
			// allow negative values, e.g. for temperature sensors
			if (this.data.min && this.data.min[1] < 0) { // set axis min to 'auto'
				ic.options.plot.yaxes[this.assignedYaxis-1].min = null;
			}
		}
	}
};





/**
 * Update UI with current entity values
 */
Entity.prototype.updateDOMRow = function() {
	var row = $('#entity-' + this.type);

	// clear table first
	$('.min', row).text('').attr('title', '');
	$('.max', row).text('').attr('title', '');
	$('.average', row).text('');
	$('.last', row).text('');
	$('.consumption', row).text('');
	$('.cost', row).text('');
	// $('.total', row).text('').data('total', null);

	if (this.data && this.data.rows > 0) { // update statistics if data available
		var yearMultiplier = 365*24*60*60*1000 / (this.data.to - this.data.from); // ms
		var unit = this.getUnit();

		// indicate stale data
		if (this.data.to)
			row.toggleClass('stale', ic.options.plot.xaxis.max - this.data.to > (ic.options.plot.stale || 24 * 3.6e6));

		if (this.data.min)
			$('.min', row)
			.text(ic.wui.formatNumber(this.data.min[1], unit))
			.attr('title', $.plot.formatDate(new Date(this.data.min[0]), '%d. %b %y %H:%M:%S', ic.options.monthNames, ic.options.dayNames, true));
		if (this.data.max)
			$('.max', row)
			.text(ic.wui.formatNumber(this.data.max[1], unit))
			.attr('title', $.plot.formatDate(new Date(this.data.max[0]), '%d. %b %y %H:%M:%S', ic.options.monthNames, ic.options.dayNames, true));
		if (this.data.average !== undefined)
			$('.average', row)
			.text(ic.wui.formatNumber(this.data.average, unit));
		if (this.data.tuples && this.data.tuples.length > 0)
			$('.last', row)
			.text(ic.wui.formatNumber(this.data.tuples[this.data.tuples.length-1][1], unit));

		if (this.data.consumption !== undefined) {
			var consumptionUnit = ic.wui.formatConsumptionUnit(unit);
			$('.consumption', row)
				.text(ic.wui.formatNumber(this.data.consumption, consumptionUnit))
				.attr('title', ic.wui.formatNumber(this.data.consumption * yearMultiplier, consumptionUnit) + '/Jahr');
		}

		if (this.cost) {
			var cost = this.cost * this.data.consumption / (this.definition.scale || 1);
			$('.cost', row)
				.data('cost', cost)
				.text(cost.toFixed(2) + ' €')
				.attr('title', (cost * yearMultiplier).toFixed(2) + ' €/Jahr');
		}
		else {
			$('.cost', row).data('cost', 0); // define value if cost property is being removed
		}
	}

	// show total value if populated
	//this.updateDOMRowTotal();

	//ic.entities.updateTableColumnVisibility();
};




/**
 * Get DOM for list of entities
 */
Entity.prototype.getDOMRow = function(parent) {
	// full or shortened type name
	var type = this.definition.translation[ic.options.language];
	if (ic.options.shortenLongTypes) type = type.replace(/\s*\(.+?\)/, '');

	var row = $('<tr>')
		.addClass((parent) ? 'child-of-entity-' + parent.type : '')
		.addClass((this.definition.model == 'Volkszaehler\\Model\\Aggregator') ? 'aggregator' : 'channel')
		.addClass('entity')
		.attr('id', 'entity-' + this.type)
		.append($('<td>')
			.addClass('visibility')
			.css('background-color', this.color)
			.append($('<input>')
				.attr('type', 'checkbox')
				.attr('checked', this.active)
				.bind('change', this, function(event) {
					var entity = event.data;
					entity.activate($(this).prop('checked'), null, true).done(ic.wui.drawPlot);
					ic.entities.saveCookie();
				})
			)
		)
		.append($('<td>').addClass('expander'))
		.append($('<td>')
			.append($('<span>')
				.addClass('indicator')
				.append($('<img>')
					.attr('src', 'img/blank.png')
					.addClass('icon-' + this.definition.icon.replace('.png', ''))
				)
				.append($('<span>')
					.text(this.title)
					// .addClass('indicator')
					// .css('background-image', this.definition.icon ? 'url(img/types/' + this.definition.icon + ')' : null)
				)
			)
		)
		.append($('<td>').addClass('type').text(type)) // channel type
		.append($('<td>').addClass('min'))		// min
		.append($('<td>').addClass('max'))		// max
		.append($('<td>').addClass('average'))		// avg
		.append($('<td>').addClass('last'))		// last value
		.append($('<td>').addClass('consumption'))	// consumption
		.append($('<td>').addClass('cost'))		// costs
		.append($('<td>').addClass('total'))	// total consumption
		.append($('<td>')				// operations
			.addClass('ops')
			.append($('<input>')
				.attr('type', 'image')
				.attr('src', 'img/blank.png')
				.addClass('icon-information')
				.attr('alt', 'details')
				.bind('click', this, function(event) {
					event.data.showDetails();
				})
			)
		)
		.data('entity', this);

/*
	if (this.cookie) {
		$('td.ops', row).prepend($('<input>')
			.attr('type', 'image')
			.attr('src', 'img/blank.png')
			.addClass('icon-delete')
			.attr('alt', 'delete')
			.bind('click', this, function(event) {
				ic.entities.splice(ic.entities.indexOf(event.data), 1); // remove
				ic.entities.saveCookie();
				ic.entities.showTable();
				ic.wui.drawPlot();
			})
		);
	}
*/

	return row;
};

