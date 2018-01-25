





/**
 * Load JSON entity details from the middleware
 */
ic.entities.loadDetails = function() {
	var queue = [];

	ic.entities.each(function(entity) {

		// Use thenable form and skip default error handling to allow modifying deferred resolution for handling
		// invalid/deleted entity uuids. Otherwise frontend loading will stall.
		queue.push(entity.loadDetails(true).then(
			function(json) {
				return $.Deferred().resolveWith(this, [json]);
			},
			function(xhr) {
				var exception = (xhr.responseJSON || {}).exception;
				// remove problematic entity
				//ic.entities.splice(ic.entities.indexOf(entity), 1); // remove
				// default error handling is skipped - be careful
				if (exception && exception.message.match(/^Invalid UUID|^No entity/)) {
					// return new resolved deferred
					return $.Deferred().resolveWith(this, [xhr]);
				}
				ic.wui.dialogs.middlewareException(xhr);
				return ic.load.errorHandler(xhr);
			}
		));
	}, true); // recursive
	return $.whenAll.apply($, queue);
};





/**
 * Load JSON data from the middleware
 */
ic.entities.loadData = function() {

	$('#overlay').html('<img src="img/loading.gif" alt="loading..." /><p>loading</p>');

	var queue = [];
	ic.entities.each(function(entity) {
		queue.push(entity.loadData());
	}, true); // recursive
	return $.when.apply($, queue);

};



/**
 * Overwritten each iterator to iterate recursively through all entities
 */
ic.entities.each = function(cb, recursive) {
	for (var i = 0; i < this.length; i++) {
		cb(this[i]);

		if (recursive && this[i] !== undefined) {
			this[i].eachChild(cb, true);
		}
	}
};