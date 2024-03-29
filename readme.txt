=== Statify – Extended Evaluation ===
Contributors: patrickrobrecht
Tags: stats, analytics, privacy, statistics
Requires at least: 4.4
Tested up to: 6.4
Requires PHP: 5.4
Stable tag: 2.6.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

This plugin evaluates the data collected with the privacy-friendly Statify Plugin (data tables and diagrams). The evaluation can be downloaded as csv.


== Description ==
This plugin evaluates the data collected with the privacy-friendly [Statify](https://wordpress.org/plugins/statify/) Plugin which is only saving date, referrer and target url for every page view.

The plugin creates evaluations for the following criteria:

* views per year / month / day
* most popular content
* views per post
* views per referrer

The results are shown in data tables and diagrams. The evaluation results can be downloaded as CSV files (for an import into LibreOffice Calc or Microsoft Excel).


== Frequently Asked Questions ==

= I have installed Statify already. Why should I install this plugin? =

This plugin provides detailed statistics for the data collected with the Statify plugin.

If you are interested in a deeper analysis of that data or want to export the evaluation as csv file, this is the right plugin for you.

= I've just installed the plugin, but I don't see any evaluation. =

If you've just installed Statify, no data is stored in the database which can be evaluated. Visit the statistics pages later again.

= How can I download the evaluation results? =

The complete data can be downloaded by clicking the *Export* button next to the headline of each evaluation.

= How do I give other users access to the *Statify – Extended Evaluation* pages? =

Per default, only administrators have access to the *Statify – Extended Evaluation* statistics.

You can give users of other roles the capability to see the *Statify – Extended Evaluation* pages by extending their capabilities with a member plugin (e. g. [Members](https://wordpress.org/plugins/members/)).

Therefore you'll have to add the *see_statify_evaluation* capability to the user role.


== Screenshots ==

1. Monthly / yearly views
2. Most popular content statistics (for the whole time Statify data were stored)
3. Referrer statistics (for the given period of time)


== Changelog ==

Please see [the changelog at GitHub](https://github.com/patrickrobrecht/extended-evaluation-for-statify/blob/master/CHANGELOG.md) for the details.

= 2.6.4 =
- Bugfix: Updated dependencies, including the Chartist library used for the charts
- Security fix: Precede cell values starting with = or another spreadsheet meta-character with a single quote to avoid CSV injection

= 2.6.3 =
* Bugfix: Index and post title tooltip in most popular posts diagram (introduced with bugfix version 2.6.2)
* Bugfix: Add selected date range to the subtitle in most popular posts diagram

= 2.6.2 =
* Bugfix: Error in most popular posts diagram showing multiple pages with the same title

= 2.6.1 =
* Update tooltip library (switch to more recent fork)
* Fix issues on WordPress multisite installations (set capabilities and links on the content page)
* Code style improvements

= 2.6.0 =
* Bugfix: Loading of minified CSS/JS, and min.js.map files
* Enhancement: tabs semantics improvements, as recommended since WordPress 5.2

= 2.5.0 =
* Enhancement: New charts library (for full GPL compatibility)
* Bugfix: Errors in selection of predefined time periods
* Bugfix: Calculation of minimum/average daily views in current month


== Upgrade Notice ==

= 2.6.4 =
This release contains a security fix and all users are encouraged to update to this version.
