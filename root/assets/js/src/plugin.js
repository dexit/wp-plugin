/**
 * {%= title %}
 * {%= homepage %}
 *
 * Copyright (c) {%= grunt.template.today('yyyy') %} {%= author_name %}
 * Licensed under the GPLv2+ license.
 */

/*jslint browser: true */
/*global jQuery:false */

window.{%= class_name %} = (function(window, document, $, undefined){
	'use strict';

	var app = {};

	app.init = function() {

	};

	$(document).ready( app.init );

	return app;

})(window, document, jQuery);
