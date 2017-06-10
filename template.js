/**
 * grunt-wp-plugin
 * https://github.com/10up/grunt-wp-plugin
 *
 * Copyright (c) 2013 Eric Mann, 10up
 * Licensed under the MIT License
 */

'use strict';

// Basic template description
exports.description = 'Create a WordPress plugin.';

// Template-specific notes to be displayed before question prompts.
exports.notes = '';

// Template-specific notes to be displayed after the question prompts.
exports.after = '';

// Any existing file or directory matching this wildcard will cause a warning.
exports.warnOn = '*';

// The actual init template
exports.template = function(grunt, init, done) {
    init.process({}, [
        // Prompt for these values.
        init.prompt('title', 'Pluginever Plugin'), {
            name: 'prefix',
            message: 'PHP function prefix (alpha and underscore characters only)',
            default: 'Pluginever'
        },
        init.prompt('textdomain', 'plvr'),
        init.prompt('constant_prefix', 'PLVR'),
        init.prompt('description', 'The best WordPress plugin ever made!'),
        init.prompt('homepage', 'http://pluginever.com'),
        init.prompt('author_name', 'PluginEver'),
        init.prompt('author_email', 'support@pluginever.com'),
        init.prompt('author_url', 'http://pluginever.com'), {
            name: 'css_type',
            message: 'CSS Preprocessor: Will you use "Sass", "LESS", or "none" for CSS with this project?',
            default: 'Sass'
        },{
            name: 'wpcs',
            message: 'WordPress Coding Standards grunt task: You need to have PHP Code Sniffer installed. Y/N',
            default: 'Y'
        }
    ], function(err, props) {
        props.keywords = [];
        props.version = '0.1.0';
        props.devDependencies = {
            'grunt': '~0.4.1',
            'grunt-contrib-concat': '~0.1.2',
            'grunt-contrib-uglify': '~0.1.1',
            'grunt-contrib-cssmin': '~0.6.0',
            'grunt-contrib-jshint': '~0.1.1',
            'grunt-contrib-nodeunit': '~0.1.2',
            'grunt-contrib-watch': '~0.2.0',
            'grunt-phpcs': '~0.2.3',
            'grunt-contrib-clean': '^1.1.0',
            'grunt-contrib-compress': '^1.4.1',
            'grunt-contrib-copy': '^1.0.0',
            "grunt-notify": "^0.4.5",
            "grunt-wp-i18n": "^1.0.0",
            "jshint-stylish": "^2.2.1"
        };

        // Sanitize names where we need to for PHP/JS
        props.name = props.title.replace(/\s+/g, '-').toLowerCase();
        // Class name
        props.class_name = props.title.replace(/\w\S*/g, function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        }).replace(/\s+/g, '_');
        // Development prefix (i.e. to prefix PHP function names, variables)
        props.prefix = props.name.replace('/[^a-z_]/i', '').toLowerCase().replace(/-/g, '_');
        // Development prefix in all caps (e.g. for constants)
        props.prefix_caps = props.prefix.toUpperCase();
        // An additional value, safe to use as a JavaScript identifier.
        props.js_safe_name = props.name.replace(/[\W_]+/g, '_').replace(/^(\d)/, '_$1');
        // An additional value that won't conflict with NodeUnit unit tests.
        props.js_test_safe_name = props.js_safe_name === 'test' ? 'myTest' : props.js_safe_name;
        props.js_safe_name_caps = props.js_safe_name.toUpperCase();
        props.wpfilename = props.js_safe_name.replace(/_/g, '-').toLowerCase();

        // Files to copy and process
        var files = init.filesToCopy(props);

        switch (props.css_type.toLowerCase()[0]) {
            case 'l':
                delete files['assets/css/sass/' + props.wpfilename + '.scss'];
                delete files['assets/css/src/' + props.wpfilename + '.css'];

                props.devDependencies["grunt-contrib-less"] = "~0.5.0";
                props.css_type = 'less';
                break;
            case 'n':
            case undefined:
                delete files['assets/css/less/' + props.wpfilename + '.less'];
                delete files['assets/css/sass/' + props.wpfilename + '.scss'];

                props.css_type = 'none';
                break;
                // SASS is the default
            default:
                delete files['assets/css/less/' + props.wpfilename + '.less'];
                delete files['assets/css/src/' + props.wpfilename + '.css'];

                props.devDependencies["grunt-contrib-sass"] = "~0.2.2";
                props.css_type = 'sass';
                break;
        }

        // var autoloader = props.autoloader.toLowerCase()[0];
        // props.autoloader = 'y' === autoloader ? true : false;

        var wpcs = props.wpcs.toLowerCase()[0];
        props.wpcs = 'y' === wpcs ? true : false;

        console.log(files);

        // Actually copy and process files
        init.copyAndProcess(files, props);

        // Generate package.json file
        init.writePackageJSON('package.json', props);

        // Done!
        done();
    });
};
