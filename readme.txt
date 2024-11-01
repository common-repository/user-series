=== User Series ===
Contributors: edm.lin
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=edm%2elin%40yahoo%2ecom&lc=CA&item_name=User%20Series&currency_code=USD
Tags: series
Requires at least: 3.4
Tested up to: 3.5.1
Stable tag: 0.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a very simple extension I wrote for Organize Series to add some access control to series.

== Description ==

Features:

1. Add Author meta data to series

2. Authors can only edit or post to their own series

3. Add Author column in manage series page

Prerequisites:

1. Organize Series

2. Taxonomy Metadata

You may also want to install User Role Editor to give Manage Series permission to Author role.

Note:

I used current_user_can('manage_options') to determine if the current user is an administrator. Feel free to tell me if you have a better idea.

== Installation ==

1. Upload `userSeries.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==


== Screenshots ==


== Changelog ==

= 0.1 =
* Initial version
