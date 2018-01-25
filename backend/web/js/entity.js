

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
 * Query middleware for details
 * @return jQuery dereferred object
 */
Entity.prototype.loadDetails = function(skipDefaultErrorHandling) {

	console.log("Details..");

	delete this.children; // clear children first

	return ic.load({
		url: this.middleware,
		controller: 'getmetrics&device_id=1',
		//identifier: this.uuid,
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





Entity.prototype.loadData = function () {

	if (!this.hasData()) {
		return $.Deferred().resolve().promise();
	}

	return ic.load({
		controller: 'data',
		url: this.middleware,
		identifier: this.uuid,
		context: this,
		data: {
			from: Math.floor(ic.options.plot.xaxis.min),
			to: Math.ceil(ic.options.plot.xaxis.max),
			tuples: ic.options.group === undefined ? ic.options.tuples : Number.MAX_SAFE_INTEGER,
			//group: ic.entities.speedupFactor()
		}
	}).done(function(json) {
		this.data = json.data;
		//this.dataUpdated();
	});
};	


/**
 * Check if data can be loaded from entity
 */
Entity.prototype.hasData = function() {
	return this.active && this.definition && this.definition.model == 'Volkszaehler\\Model\\Channel';
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



/*
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
*/

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


