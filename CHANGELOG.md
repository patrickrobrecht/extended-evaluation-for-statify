# *Statify – Extended Evaluation* - Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased


## Version 2.6.5

### Changed
- Update dependencies, including the Chartist library used for the charts
- Replace outdated Chartist tooltip plugin with a custom implementation
- Configure gitattributes, force tabs (as enforced by WP Coding Standards)


## Version 2.6.4

### Changed
- Update dependencies, including the Chartist library used for the charts

### Security
- Precede cell values starting with = or another spreadsheet meta-character with a single quote to avoid CSV injection


## Version 2.6.3

### Fixed
- Index and post title tooltip in most popular posts diagram (introduced with bugfix version 2.6.2)
- Add selected date range to the subtitle in most popular posts diagram


## Version 2.6.2

### Fixed
- Error in most popular posts diagram showing multiple pages with the same title


## Version 2.6.1

### Changed
- Update tooltip library (switch to more recent fork)
- Improve code style

### Fixed
- Fix issues on WordPress multisite installations (set capabilities and links on the content page)


## Version 2.6.0

### Changed
- Enhancement: tabs semantics improvements, as recommended since WordPress 5.2

### Fixed
- Loading of minified CSS/JS, and min.js.map files


## Version 2.5.0
- New charts library (for full GPL compatibility)

### Fixed
- Errors in selection of predefined time periods
- Calculation of minimum/average daily views in current month


## Version 2.4

### Added
- Selection of predefined time periods

### Changed
- Better selection of posts
- Minified CSS/JS files (loaded on plugin pages only), build command
- Check for compliance with WordPress Coding Guidelines with PHP_CodeSniffer
- Refactoring the charts code


## Version 2.3.1

### Fixed
- diagram for yearly views


## Version 2.3

### Changed
- Better conformance to WordPress Coding Guidelines
- Rename the plugin to *Statify – Extended Evaluation* such that it is listed after to Statify in the alphabetical list of plugins in the WordPress admin area

### Fixed
- Remove buggy Statify Analyst user role. Use [Members](https://wordpress.org/plugins/members/) to add the capability *see_statify_evaluation* to other roles than administrator.
- Proper escaping for all outputs


## Version 2.2

### Changed
- Minor changes in the style
- If there is nothing to display, show "no data available"


## Version 2.1

### Changed
- URLs in csv export
- percentages on Content and Referrers pages


## Version 2.0

### Changed
- Average/minimum/maximum daily views per month on Dashboard page
- Simplify code


## Version 1.9

### Changed
- Client-side CSV export via JavaScript
- Improved database queries

### Fixed
- Daily diagram now includes values with 0 views to display the diagram correctly


## Version 1.8

### Changed
- Formatted dates in diagram subtitles
- Translate filenames for diagrams, consistent with csv export filenames


## Version 1.7

### Changed
- Statistics for one selected post/page on the dashboard and referrer page


## Version 1.6

### Changed
- CSV export for content statistics with posts containing special characters


## Version 1.5

### Added
- Referrers for variable period
- Most popular content and post views for variable period

### Changed
- Content / referrer diagrams: only first 25 values


## Version 1.4

### Added
- Diagrams for all evaluations
- Download diagrams as PNG, JPG, PDF or SVG


## Version 1.3.1

### Fixed
- Capability *see_statify_evaluation* for access to the menu pages


## Version 1.3

### Changed
- Dashboard with monthly evaluation and tabs for daily views
- Content page now with tabs for most popular content and all post types


## Version 1.2

### Changed
- Text domain *extended-evaluation-for-statify* for localization


## Version 1.1

### Added
- Own capability for access to the evaluation pages.

### Changed
- Menu page now before Design page and not at the very end.

### Fixed
- *All content* link on the dashboard page


## Version 1.0

### Added
- Show views per day / month / year
- Show most popular content (title, url, post type and views)
- Show views per post/page/custom post type (title, url and views)
- Show referrer statistics (domain and views)
- Export all statistics listed above as csv files
