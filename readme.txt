=== Extended Evaluation for Statify ===
Contributors: patrickrobrecht
Tags: stats, analytics, privacy, dashboard, statistics, admin
Requires at least: 4.4
Tested up to: 4.6.1
Stable tag: 1.8
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

This plugin evaluates the data collected with the privacy-friendly Statify Plugin (data tables and diagrams). The evaluation can be downloaded as csv.


== Description ==
This plugin evaluates the data collected with the privacy-friendly Statify Plugin which is only saving date, referrer and target url for every page view.

The plugin creates evaluations for the following criteria:

* views per year / month / day
* most popular content
* views per post
* views per referrer

The results are shown in data tables and diagrams. The evaluation results can be downloaded

* as CSV files (for an import into LibreOffice Calc or Microsoft Excel)
* as PNG, JPG or SVG image or PDF document (all diagrams) using the [Highcharts](http://www.highcharts.com/) JavaScript library

The plugin is currently available in English and German. If you need another language, please help to [translate this plugin](https://translate.wordpress.org/projects/wp-plugins/extended-evaluation-for-statify).

= Requirements =

* WordPress 4.4+ (not tested with older WordPress versions)
* [Statify](https://wordpress.org/plugins/statify/) 1.4.2+
* PHP 5.6+ and 7.0+ (not tested with older PHP versions)

This plugin will be regularly updated to be compatibe with the latest WordPress and Statify version!


== Installation ==
1. If not already done, install and activate the **[Statify plugin](https://wordpress.org/plugins/statify/)** which is necessary to collect the data to evaluate.
2. Go to Plugins > Add New.
3. Search for *Statify* and install *Extended Evaluation for Statify* 
	(alternative: Download the zip file from wordpress.org and upload the files to the `/wp-content/plugins/` directory).
4. Activate *Extended Evaluation for Statify* in the WordPress Plugins Area.
5. See the evalutation of the Statify data in the new menu page *Statify*. 
	(Important notice: If you've just activated Statify, there will be nothing interesting for now because there's no data to evaluate. So come back to the *Extended Evaluation for Statify* page later!)


== Frequently Asked Questions ==

For the list of features and the minimum requirements please check the [plugin's main page](https://wordpress.org/plugins/extended-evaluation-for-statify/). A list of recent changes find in the [changelog](https://wordpress.org/plugins/extended-evaluation-for-statify/changelog/).

= I have installed Statify already. Why should I install Extended Evaluation for Statify? =

*Extended Evaluation for Statify* provides detailed statistics for the data collected with the Statify plugin. 
If you are interested in a deeper analysis of that data or want to export the evaluation as csv file, this is the right plugin for you.

= I've just installed the plugin, but I don't see any evaluation. =
If you've just installed Statify, no data is stored in the database which can be evaluated. Visit the *Extended Evaluation for Statify* pages later again and you'll see the statistics. 

= How can I download the evaluation results? =

The complete data can be downloaded by clicking the *Export* button next to the headline of each evaluation.

The diagrams can be downloaded by clicking the menu in the upper right corner of the diagram and choosing one of the dowload options (available: PNG image, JPG image, PDF document, SVG image).

= How do I give other users access to the Extended Evaluation for Statify pages? =

Per default, administrators and analysts have access to the *Extended Evaluation for Statify* pages.
Users with the role analyst can only see the Statify evaluation and nothing else within the WordPress admin area.

You can give users of other roles the capability to see the *Extended Evaluation for Statify* page by extending their capabilities with a member plugin (e. g. [Members](https://wordpress.org/plugins/members/)).
Therefore you'll have to add the *see_statify_evaluation* capability to the user role.


== Screenshots ==

1. Monthly / yearly views
2. Most popular content statistics (for the whole time Statify data were stored)
3. Referrer statistics (for the given period of time)


== Changelog ==

Please see https://github.com/patrickrobrecht/extended-evaluation-for-statify for the details.

= 1.7 =
* Enhancement: statistics for one selected post/page on the dashboard and referrer page

= 1.6 =
* Bugfix: csv export for content statistics with posts containing special characters

= 1.5 =
* Feature: Referrers for variable period
* Feature: Most popular content and post views for variable period
* Enhancement: content / referrer diagrams: only first 25 values

= 1.4 =
* Feature: diagrams for all evaluations
* Feature: download diagrams as PNG, JPG, PDF or SVG


== Upgrade Notice ==

= 1.7 =
Enhancement: statistics for one selected post/page on the dashboard and referrer page 

= 1.6 =
One bug fix in the csv export.

= 1.5 =
New feature: Content / Referrers per date period.

= 1.4 =
New feature: diagrams.
