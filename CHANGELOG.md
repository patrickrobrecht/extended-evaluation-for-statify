# *Statify – Extended Evaluation* - Changelog

## Version 2.x

### Unreleased
* Refactoring the charts code

### Version 2.3.1
* Bugfix: diagram for yearly views

### Version 2.3
* Bugfix: Remove buggy Statify Analyst user role. Use [Members](https://wordpress.org/plugins/members/) to add the capability *see_statify_evaluation* to other roles than administrator.
* Bugfix: Proper escaping for all outputs
* Enhancement: Better conformance to WordPress Coding Guidelines
* Enhancement: Rename the plugin to *Statify – Extended Evaluation* such that it is listed after to Statify in the alphabetical list of plugins in the WordPress admin area

### Version 2.2
* Enhancement: Minor changes in the style
* Enhancement: If there is nothing to display, show "no data available"

### Version 2.1
* Enhancement: URLs in csv export
* Enhancement: percentages on Content and Referrers pages

### Version 2.0
* Enhancement: average/minimum/maximum daily views per month on Dashboard page
* Enhancement: code simplifications

## Version 1.x

### Version 1.9
* Enhancement: client-side CSV export via JavaScript
* Improved database queries
* Bugfix: Daily diagram now includes values with 0 views to display the diagram correctly

### Version 1.8
* Enhancement: Formatted dates in diagram subtitles
* Enhancement: translated filenames for diagrams, consistent with csv export filenames

### Version 1.7
* Enhancement: statistics for one selected post/page on the dashboard and referrer page

### Version 1.6
* Bugfix: csv export for content statistics with posts containing special characters

### Version 1.5
* Feature: Referrers for variable period
* Feature: Most popular content and post views for variable period
* Enhancement: content / referrer diagrams: only first 25 values

### Version 1.4
* Feature: diagrams for all evaluations
* Feature: download diagrams as PNG, JPG, PDF or SVG

### Version 1.3.1
* Bugfix: Capability *see_statify_evaluation* for access to the menu pages

### Version 1.3
* Enhancement: Dashboard with monthly evaluation and tabs for daily views
* Enhancement: Content page now with tabs for most popular content and all post types

### Version 1.2
* Enhancement: Text domain *extended-evaluation-for-statify* for localization

### Version 1.1
* Feature: Own capability for access to the evaluation pages.
* Enhancement: Menu page now before Design page and not at the very end.
* Bugfix: fixed *All content* link on the dashboard page

### Version 1.0
* Feature: show views per day / month / year
* Feature: show most popular content (title, url, post type and views)
* Feature: show views per post/page/custom post type (title, url and views)
* Feature: show referrer statistics (domain and views)
* Feature: export all statistics listed above as csv files
