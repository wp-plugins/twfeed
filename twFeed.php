<?php
/*
Plugin Name: twFeed
Plugin URI: http://www.paulcormack.net/projects#twFeed
Description: Integrates a Twitter RSS feed into your blog. Comes widget ready or by adding <code>&lt;?php get_twFeed(username,num_posts); ?&gt;</code> directly in a template file.
Version: 0.3
License: GPLV2
Author: Paul Cormack
Author URI: http://www.paulcormack.net
*/

function get_twFeed($twFeed_usr,$twFeed_show){

	# check input
	if ($twFeed_show > 20) {
		$twFeed_show = 20;
	}

	$twFeed_before = '<ul class="twFeed">';
	$twFeed_after = '</ul>';
	# build url
	$twFeed_url = "http://twitter.com/statuses/user_timeline/".$twFeed_usr.".rss";
	
	if (!function_exists('MagpieRSS')){
		include_once (ABSPATH . WPINC . '/rss.php');
		error_reporting(E_ERROR);
	}

	# get all contents from rss feed$twFeed_tweet
	$twFeed_full_contents = @fetch_rss($twFeed_url);
	# if there is data then process
	if ($twFeed_full_contents) {
		# array restriction
		$twFeed_contents = array_slice($twFeed_full_contents->items, 0, $twFeed_show);

		echo $twFeed_before;
		# generate html for each tweet
		foreach( $twFeed_contents as $tweet ) {
			$twFeed_desc = $tweet['description'];
			# remove username from tweet description and parse
			$twFeed_tweet = str_replace($twFeed_usr .":",'', $twFeed_desc);
			$twFeed_tweet = htmlspecialchars(stripslashes($twFeed_tweet));
			# parse http/https strings into html
			$twFeed_tweet = preg_replace(
				'@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@',
				'<a href="$1" title="$1" alt="$1" target="_blank">$1</a>', $twFeed_tweet);
			echo "<li>".$twFeed_tweet."</li>";
		}
		echo $twFeed_after;
	}

	else {
		print "RSS problems, check twitter username...";
	}
}

function twFeed_widget($args) {
	extract($args);
	$options = get_option('widget_twFeed');
	$twFeed_title = "@paul_cormack";
	echo '<h2 class="widgettitle">'.$options['title'].'</h2>';
	get_twFeed($options['user'],$options['posts']);
}

function twFeed_control(){
	$options = get_option('widget_twFeed');
	if ( $_POST['twFeed-submit'] ) {
		$options['title'] = strip_tags(stripslashes($_POST['twFeed-wtitle']));
		$options['user'] = strip_tags(stripslashes($_POST['twFeed-tuser']));
		$options['posts'] = strip_tags(stripslashes($_POST['twFeed-posts']));
		update_option('widget_twFeed', $options);
	}
	
	# set defaults if there are none passed
	if (!is_array( $options )) {
  		$options = array('title'=>'Tweets','user'=>'twitter','posts'=>5);
	}

	$title = htmlspecialchars($options['title'], ENT_QUOTES);
	$settingspage = trailingslashit(get_option('siteurl')).'wp-admin/options-general.php?page='.basename(__FILE__);
	
	echo '<p><label for="twFeed-wtitle">Widget Title:<input class="widefat" name="twFeed-wtitle" type="text" value="'.$options['title'].'" /></label></p>'.'<p><label for="twFeed-tuser">username:<input class="widefat" name="twFeed-tuser" type="text" value="'.$options['user'].'" /></label></p>'.'<p><label for="twFeed-posts">Number of tweets: (Max 20)<input class="widefat" name="twFeed-posts" type="text" value="'.$options['posts'].'" /></label></p>'.'<input type="hidden" id="twFeed-submit" name="twFeed-submit" value="1" />';

}
	
function twFeed_init(){
	register_sidebar_widget('twFeed','twFeed_widget');
	register_widget_control('twFeed','twFeed_control');
}
add_action("plugins_loaded", "twFeed_init");

?>
