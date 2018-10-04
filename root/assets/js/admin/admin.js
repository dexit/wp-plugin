/**
 * {%= title %} Admin
 * {%= homepage %}
 *
 * Copyright (c) {%= grunt.template.today('yyyy') %} {%= author_name %}
 * Licensed under the GPLv2+ license.
 */

/*jslint browser: true */
/*global jQuery:false */
/*global {%= js_safe_name %}:false */
jQuery(document).ready(function ($, window, document, undefined) {
    'use strict';
    $.{%= js_safe_name %} = {
        init: function () {

        }
    };


    $.{%= js_safe_name %}.init();
});