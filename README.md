# WordPress Plugin *Statify – Extended Evaluation*

This WordPress plugin evaluates the data collected with the privacy-friendly Statify Plugin (data tables and diagrams). The evaluation can be downloaded as csv and as diagram (image or PDF).

## How to use this plugin

The use of this WordPress Plugin requires the [Statify Plugin](https://de.wordpress.org/plugins/statify/) to be installed and activated.

Details, screenshot and the zip file download of the latest stable version can be found in the [WordPress Plugin Directory](https://wordpress.org/plugins/extended-evaluation-for-statify/).


## How to develop

Install [Composer](https://getcomposer.org/)
    and run `composer install` to install the dependencies for development.

`composer build` creates the release version in the `dist` directory
    (it contains exactly the files necessary for wordpress.org).

`composer cs` checks the code style
    and reports any violations of the WordPress Coding Guidelines
    (see `phpcs.xml` for details).
 
`composer csfix` tries to fix code style issues.

`composer minify` generates minified CSS and JavaScript files.


## Development and Translations

You can report issues or suggest new features in the [Issue Tracker](https://github.com/patrickrobrecht/extended-evaluation-for-statify/issues). If you experience unexpected behavior, please ask in the [WordPress Support Forum](https://wordpress.org/support/plugin/extended-evaluation-for-statify) first. Pull requests for known issues are highly appreciated!

You can help to [translate the plugin](https://translate.wordpress.org/projects/wp-plugins/extended-evaluation-for-statify) to further languages.

Developer: [Patrick Robrecht](https://patrick-robrecht.de/)
