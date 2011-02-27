=== Plugin Name ===
Contributors: pcormack
Donate link: none
Tags: twitter, rss, widget, feed
Requires at least: 2.1
Tested up to: 3.1
Stable tag: 0.3

Integrates a Twitter RSS feed into your blog. Comes widget ready or by creating a new object directly in a php template file.

== Description ==

Integrates a Twitter RSS feed into your blog. Comes widget ready or by adding get_twFeed("username",num_posts); directly in a template file.

* Basic plugin, early development stage.
* Displays tweets in a unordered list so output can be styled with CSS.
* Tweet links are opened in a new window or tab.
* Defaults to 5 posts from my twitter account.

== Installation ==

1. Extract archive and upload the 'twFeed' folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Navigate to 'Plugins' > 'Installed' in the WP admin dashboard. Locate twFeed and click activate.
1. Navigate to 'Appearance' > 'Widgets' in the WP admin dashboard. Expand widget to enter the title, Twitter username and number of tweets to display. 
1. Optional: Add to php template files:

* $new_feed = new twFeed();
* $new_feed->get_twFeed("paul_cormack",10);

== Frequently Asked Questions ==

= Why a limit of 20 tweets? =

The RSS feed is limited to 20 results.

= Can the plugin be used multiple times? =

Plugin yes, widget only once.

= How can CSS be used to style a feed? =

An example using the sidebar:

* div#sidebar ul.twFeed{  }
* div#sidebar ul.twFeed li{  }

== Screenshots ==

1. Widget Screen
2. twFeed @wordpress Sidebar Demo

== Changelog ==

= 0.4 =
* Complete code restructure using a class layout with object calls.
* Changed default user from @twitter to @paul_cormack.

= 0.3 =
* Initial release

== Upgrade Notice ==

= 0.4 =
* Due to the code restructuring, usage outside of widget area has changed. See installation section for more information.
