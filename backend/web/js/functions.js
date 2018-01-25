


var Exception = function(type, message, code) {
	return {
		type: type,
		message: message,
		code: code
	};
};


/**
 * Universal helper for middleware ajax requests with error handling
 *
 * @param skipDefaultErrorHandling according to http://stackoverflow.com/questions/19101670/provide-a-default-fail-method-for-a-jquery-deferred-object
 */
ic.load = function(args, skipDefaultErrorHandling) {

	console.log("Loading...");

	$.extend(args, {
		accepts: 'application/json',
		beforeSend: function (xhr, settings) {
			// remember URL for potential error messages
			xhr.requestUrl = settings.url;
		}
	});

	if (args.url === undefined) { // local middleware by default
		args.url = ic.options.middleware[0].url;
		//args.url = '/index.php?r=timeseries/getdata';
	}

	if (args.controller !== undefined) {
		args.url += '/' + args.controller;
	}

/*
	if (args.identifier !== undefined) {
		args.url += '/' + args.identifier;
	}
*/

	//args.url += '.json';

/*
	// workaround Safari 11 cache bug
	if (args.method === undefined || args.method == 'GET') {
		args.url += '?unique=' + Date.now();
	}
*/
	if (args.data === undefined) {
		args.data = { };
	}

	return $.ajax(args).then(
		// success
		function(json, error, xhr) {
			// ensure json response - might still be server error
			if (!xhr.responseJSON) {
				ic.load.errorHandler(xhr);
				$.Deferred().rejectWith(this, [xhr]);
			}
			return $.Deferred().resolveWith(this, [json]);
		},
		// error
		function(xhr) {
			return ic.load.errorHandler(xhr, skipDefaultErrorHandling);
		}
	);
};


/**
 * Reusable authorization-aware error handler
 */
ic.load.errorHandler = function(xhr, skipDefaultErrorHandling) {
	if (!skipDefaultErrorHandling) {
		ic.wui.dialogs.middlewareException(xhr);
	}
	return xhr;
};

















/**
 * Middleware constructor
 * @var middleware definition
 */
var Middleware = function(definition) {
	this.title = definition.title;
	this.url = definition.url;
	this.live = definition.live || null;

	this.public = [];
	this.session = null;
};

/**
 * Load public entities
 */
Middleware.prototype.loadEntities = function() {
	return ic.load({
		controller: 'entity',
		url: this.url,
		context: this
	}).then(function(json) {
		this.public = [];

		json.entities.forEach(function(json) {
			// fix https://github.com/volkszaehler/volkszaehler.org/pull/560
			json.active = true;
			var entity = new Entity(json, this.url);
			entity.eachChild(function(child) {
				child.active = true;
			}, true); // recursive
			this.public.push(entity);
		}, this);
		this.public.sort(Entity.compare);

		// chainable
		return this;
	});
};






/**
 * jQuery extensions
 */
(function($) {

	/**
	 * Deferred script loading
	 */
	$.cachedScript = function(url, options) {
		// Allow user to set any option except for dataType, cache, and url
		options = $.extend(options || {}, {
			dataType: "script",
			cache: true,
			url: url
		});
		// Use $.ajax() since it is more flexible than $.getScript
		// Return the jqXHR object so we can chain callbacks
		return $.ajax(options);
	};

	/**
	 * Serialize form including unchecked checkboxes
	 * http://stackoverflow.com/questions/3029870/jquery-serialize-does-not-register-checkboxes
	 *
	 * @todo make off value and default selection configurable
	 */
	$.fn.serializeArrayWithCheckBoxes = function() {
		// serialize form the non-checkbox fields
		return $(this).serializeArray()
		// add values for unchecked checkbox fields
		.concat(
			$(this).find("input[type=checkbox]:not(:checked)").map(function() {
				return { "name": this.name, "value": "0" };
			}).get()
		);
	};

  var slice = [].slice;

  // https://gist.github.com/fearphage/4341799
  $.whenAll = function(array) {
    var
			/* jshint laxbreak: true */
      resolveValues = arguments.length == 1 && $.isArray(array)
        ? array
        : slice.call(arguments),
      length = resolveValues.length,
      remaining = length,
      deferred = $.Deferred(),
      i = 0,
      failed = 0,
      rejectContexts = Array(length),
      rejectValues = Array(length),
      resolveContexts = Array(length),
      value
    ;

    function updateFunc (index, contexts, values) {
      return function() {
        if (values !== resolveValues) {
          failed++;
        }
        deferred.notifyWith(
         contexts[index] = this,
         values[index] = slice.call(arguments)
        );
        if (!(--remaining)) {
          deferred[(!failed ? 'resolve' : 'reject') + 'With'](contexts, values);
        }
      };
    }

    for (; i < length; i++) {
      if ((value = resolveValues[i]) && $.isFunction(value.promise)) {
        value.promise()
          .done(updateFunc(i, resolveContexts, resolveValues))
          .fail(updateFunc(i, rejectContexts, rejectValues))
        ;
      }
      else {
        deferred.notifyWith(this, value);
        --remaining;
      }
    }

    if (!remaining) {
      deferred.resolveWith(resolveContexts, resolveValues);
    }

    return deferred.promise();
  };

})(jQuery);


