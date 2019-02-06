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
exports.template = function (grunt, init, done) {
    init.process({}, [
        init.prompt('title', 'WP Plugin'),
        init.prompt('unique_prefix', 'PLVR'),
        init.prompt('description', 'The Best WordPress Plugin ever made!'),
        init.prompt('homepage', 'https://www.pluginever.com'),
        init.prompt('author_name', 'pluginever'),
        init.prompt('author_email', 'support@pluginever.com'),
        init.prompt('author_url', 'https://www.pluginever.com'),
    ], function (err, props) {
        props.keywords = [];
        props.version = '1.0.0';

        //sanitize the plugin name
        props.title = props.title
            .replace(/^wp/gi, 'WP')
            .replace(/^wc/gi, 'WC')
            .replace(/^WooCommerce/gi, 'WC')
        
        var plugin_slug = props.title.replace(/\s+/g, '-').toLowerCase();
        var function_prefix = plugin_slug.replace(/^wp?-/g, '').replace(/\-/g, '_');

        props.name = plugin_slug;
        props.slug = plugin_slug;
        props.text_domain = plugin_slug;
        props.function_prefix = function_prefix;
        props.prefix = props.unique_prefix;
        props.js_safe_name = plugin_slug.replace(/-/g, '_');
        props.class_name = props.title.replace(/^wp/gi, '').replace(/\s/g, '');
        props.wpfilename = plugin_slug;
        props.js_object = props.unique_prefix.toLocaleLowerCase();
        props.scripts = {
            "build": "grunt",
            "build-watch": "grunt watch"
        };
        props.devDependencies = {
            "autoprefixer": "~8.6.2",
            "babel": "^6.5.2",
            "babel-cli": "^6.14.0",
            "babel-eslint": "^8.2.3",
            "babel-plugin-add-module-exports": "^0.2.1",
            "babel-preset-es2015": "^6.14.0",
            "babel-preset-stage-2": "^6.13.0",
            "config": "^1.24.0",
            "cross-env": "^5.1.6",
            "grunt": "^1.0.3",
            "grunt-checktextdomain": "~1.0.1",
            "grunt-contrib-clean": "~1.1.0",
            "grunt-contrib-compress": "^1.4.3",
            "grunt-contrib-concat": "~1.0.1",
            "grunt-contrib-copy": "^1.0.0",
            "grunt-contrib-cssmin": "~2.2.1",
            "grunt-contrib-jshint": "~1.1.0",
            "grunt-contrib-uglify": "~3.3.0",
            "grunt-contrib-watch": "^1.1.0",
            "grunt-phpcs": "~0.4.0",
            "grunt-postcss": "~0.9.0",
            "grunt-prompt": "^1.3.3",
            "grunt-sass": "~2.1.0",
            "grunt-stylelint": "~0.10.0",
            "grunt-wp-i18n": "~1.0.1",
            "stylelint": "~9.2.1"
        };
        props.engines = {
            "node": ">=8.9.3",
            "npm": ">=5.5.1"
        };
        
        console.log(props);
        // Files to copy and process
        var files = init.filesToCopy(props);

        // Actually copy and process files
        init.copyAndProcess(files, props);

        // Generate package.json file
        init.writePackageJSON('package.json', props);

        // Done!
        done();
    });
};
