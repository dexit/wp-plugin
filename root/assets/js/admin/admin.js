/**
 * {%= title %} Admin
 * {%= homepage %}
 *
 * Copyright (c) {%= grunt.template.today('yyyy') %} {%= author_name %}
 * Licensed under the GPLv2+ license.
 */

/*jslint browser: true */
/*global jQuery:false */

window.{%= js_safe_name %}_admin = (function(window, document, $, undefined){
	'use strict';

    var app = {

        initialize: function(){

        }
    };

    $(document).ready( app.initialize );

	return app;

})(window, document, jQuery);

jQuery(document).ready(function ($, window, document, undefined) {
	'use strict';
	$.wc_variation_images = {
        init: function () {
			
		}
    }