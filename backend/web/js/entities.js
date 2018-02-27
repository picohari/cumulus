




/**
 * Load JSON entity details from the middleware
 */
ic.entities.loadMetrics = function() {
	var queue = [];

	ic.entities.each(function(entity) {

		// Use thenable form and skip default error handling to allow modifying deferred resolution for handling
		// invalid/deleted entity uuids. Otherwise frontend loading will stall.
		queue.push(entity.loadMetrics(true).then(
			function(json) {
				return $.Deferred().resolveWith(this, [json]);
			},
			function(xhr) {
				var exception = (xhr.responseJSON || {}).exception;
				// remove problematic entity
				ic.entities.splice(ic.entities.indexOf(entity), 1); // remove
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
				ic.entities.splice(ic.entities.indexOf(entity), 1); // remove
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
		queue.push(entity.loadData(entity.type));
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





/**
 * Create nested entity list
 * @todo move to Entity class
 */
ic.entities.showTable = function() {
	$('#entity-list tbody').empty();
	ic.entities.sort(Entity.compare);

	// add entities to table (recurse into aggregators)
	ic.entities.each(function(entity, parent) {
		if (entity.definition) { // skip bad entities, e.g. without data
			$('#entity-list tbody').append(entity.getDOMRow(parent));
		}
	}, true);

	/*
	 * Initialize treeTable
	 *
	 * http://ludo.cubicphuse.nl/jquery-plugins/treeTable/doc/index.html
	 * https://github.com/ludo/jquery-plugins/tree/master/treeTable
	 */
	 /*
	// configure entities as draggable
	$('#entity-list tr.channel span.indicator, #entity-list tr.aggregator span.indicator').draggable({
		helper:  'clone',
		opacity: 0.75,
		refreshPositions: true, // Performance?
		revert: 'invalid',
		revertDuration: 300,
		scroll: true
	});

	// configure thead and aggregators as droppable
	$('#entity-list tr.aggregator span.indicator, #entity-list thead tr th:first').each(function() {
		$(this).parents('tr').droppable({
			accept: '#entity-list tr.channel span.indicator, #entity-list tr.aggregator span.indicator',
			drop: function(event, ui) {
				var child = $(ui.draggable.parents('tr')[0]).data('entity');
				if (child === null)
					return; // no data for the dropped object, probably not a row
				var from = child.parent;
				var to = $(this).data('entity');
				if (to === child)
					return; // drop on itself -> do nothing
				if (from === to)
					return; // drop into same group -> do nothing
				if (to && to.definition.model == 'Volkszaehler\\Model\\Aggregator' && $.inArray(child, to.children) >= 0)
					return;
				if (to && child.middleware !== to.middleware) {
					ic.wui.dialogs.error("Fehler", "Kanäle können nur in Gruppen der gleichen Middleware verschoben werden.");
					return;
				}

				$('#entity-move').dialog({ // confirm prompt
					resizable: false,
					modal: true,
					title: 'Verschieben',
					width: 400,
					buttons: {
						'Verschieben': function() {
							ic.entities.dropTableHandler(from, to, child, false);
							$(this).dialog('close');
						},
						'Kopieren': function() {
							ic.entities.dropTableHandler(from, to, child, true);
							$(this).dialog('close');
						},
						'Abbrechen': function() {
							$(this).dialog('close');
						}
					}
				});
			},
			hoverClass: 'accept',
			over: function(event, ui) {
				// make the droppable branch expand when a draggable node is moved over it
				if (this.id != $(ui.draggable.parents('tr')[0]).id && !$(this).hasClass('expanded')) {
					$(this).expand();
				}
			}
		});
	});

	// make visible that a row is clicked
	$('#entity-list table tbody tr').mousedown(function() {
		var entity = $(this).data('entity');
		var selected = $('tr.selected');

		selected.removeClass('selected'); // deselect currently selected rows
		ic.wui.selectedChannel = null;

		if (entity !== selected.data('entity') && entity.active) {
			$(this).addClass('selected');
			ic.wui.selectedChannel = entity.uuid;
		}
		ic.wui.drawPlot();
	});

	$('#entity-list table').treeTable({
		treeColumn: 2,
		clickableNodeNames: true,
		initialState: 'expanded'
	});

	ic.entities.updateTableColumnVisibility();
	*/
	
	// display the data we have already
	ic.entities.each(function(entity) {
		entity.updateDOMRow();
	}, true); // recursive
};
