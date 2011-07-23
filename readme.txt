=== Plugin Name ===
Contributors: pcormack
Donate link: none
Tags: twitter, rss, widget, feed
Requires at least: 2.1
Tested up to: 3.2.1
Stable tag: 0.3

Integrates a Twitter RSS feed into your blog. Comes widget ready.

== Description ==

* Displays tweets in a unordered list so output can be styled with CSS.
* Tweet links are opened in a new window or tab.
* Defaults to 5 posts from my twitter account.
* Links @mentions.

== Installation ==

1. Extract archive and upload the 'twFeed' folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Navigate to 'Plugins' > 'Installed' in the WP admin dashboard. Locate twFeed and click activate.
1. Navigate to 'Appearance' > 'Widgets' in the WP admin dashboard. Expand widget to enter the title, Twitter username and number of tweets to display. 

== Frequently Asked Questions ==

= What are the limitations? =

The twitter RSS feed is limited to 20 results and has certain a reload time.

= Can the plugin be used multiple times? =

Yes, with different users in the same or different widget areas.

= Styling With CSS =

`ul.twFeed{  }`
`ul.twFeed li{  }`
`a.twFeed_mention{ color:red; }`
`a.twFeed_date{ color:green; }`

== Screenshots ==

1. Admin widget screenshot
2. twFeed @wordpress Sidebar

== Changelog ==

= 1.1  =
* Fixed @mention bug which removed the preceding space from links.

= 1.0 =
* Rewrote class to extend `WP_Widget`.
* Updated regular expressions.
* Calls within PHP template pages have changed. (See installation).
* Added linking @mentions as default.
* Added option to display tweet time.
* Updated screenshots.

= 0.4.1 =
* Fixed a regular expression bug pointed out by @compywiz on Twitter. (String contents like 1.5GHz turns into a clickable link).

= 0.4 =
* Complete code restructure using a class layout with object calls.
* Changed default user from @twitter to @paul_cormack.

= 0.3 =
* Initial release

== Upgrade Notice ==

= 0.5 =
* Changed argument structure for object calls within PHP template files.
* See installation if you use twFeed within PHP template files.

= 0.4 =
* Due to the code restructuring, usage outside of widget area has changed. See installation section for more information.
