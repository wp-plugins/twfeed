<?php
/*
Plugin Name: twFeed
Plugin URI: http://www.paulcormack.net/projects#twFeed
Description: Integrates a Twitter RSS feed into your blog. Comes widget ready or by creating a new object and calling the function directly in php template files.
Version: 0.4.1
License: GPLV2
Author: Paul Cormack
Author URI: http://www.paulcormack.net
*/

add_action("widgets_init", array('twFeed', 'register'));

class twFeed {

	function get_twFeed($twFeed_usr,$twFeed_posts){
		
		if ($twFeed_show > 20) {
			$twFeed_show = 20;
		}

		$twFeed_before = '<ul class="twFeed">';
		$twFeed_after = '</ul>';
		$twFeed_url = "http://twitter.com/statuses/user_timeline/".$twFeed_usr.".rss";
		
		if (!function_exists('MagpieRSS')){
			include_once (ABSPATH . WPINC . '/rss.php');
		}

		$twFeed_full_contents = @fetch_rss($twFeed_url);
		
		if ($twFeed_full_contents) {
			$twFeed_contents = array_slice($twFeed_full_contents->items, 0, $twFeed_posts);
			
			echo $twFeed_before;
			foreach( $twFeed_contents as $tweet ) {
				$twFeed_desc = $tweet['description'];
				#$twFeed_time = $tweet['pubDate'];
				$twFeed_tweet = str_replace($twFeed_usr.":",'', $twFeed_desc);
				$twFeed_tweet = htmlspecialchars(stripslashes($twFeed_tweet));
				$url_regex = '`\b(https?|ftp)://[-A-Za-z0-9+@#/%?=~_|!:,.;]*[-A-Za-z0-9+@#/%=~_|]\b`';
				$twFeed_tweet = preg_replace($url_regex, '<a href="\0" title="\0" alt="\0" target="_blank">\0</a>', $twFeed_tweet);
				echo "<li>".$twFeed_tweet."</li>";
			}
			echo $twFeed_after;
		}

		else {
			print "RSS problems, check twitter username...";
		}
	}

	function control(){
    	$options = get_option('widget_twFeed');
		if ( $_POST['twFeed-submit'] ) {
		
			$options['title'] = strip_tags(stripslashes($_POST['twFeed-wtitle']));
			$options['user'] = strip_tags(stripslashes($_POST['twFeed-tuser']));
			$options['posts'] = strip_tags(stripslashes($_POST['twFeed-posts']));
			$options['dates'] = strip_tags(stripslashes($_POST['twFeed-dates']));
			$option = isset($options['option']) ? $options['option'] : false;
			update_option('widget_twFeed', $options);
			
		}	
		
		if (!is_array( $options )) {
	  		$options = array(
	  			'title'=>'Tweets',
	  			'user'=>'paul_cormack',
	  			'posts'=>5,
	  			'dates'=>false
	  		);
		}
		
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$settingspage = trailingslashit(get_option('siteurl')).'wp-admin/options-general.php?page='.basename(__FILE__);
		echo '<p><label for="twFeed-wtitle">Widget Title:<input class="widefat" name="twFeed-wtitle" type="text" value="'.$options['title'].'" /></label></p>'.'<p><label for="twFeed-tuser">Twitter user:<input class="widefat" name="twFeed-tuser" type="text" value="'.$options['user'].'" /></label></p>'.'<p><label for="twFeed-posts">Number of tweets: (Max 20)<input class="widefat" name="twFeed-posts" type="text" value="'.$options['posts'].'" /></label></p>'.'<input type="hidden" id="twFeed-submit" name="twFeed-submit" value="1" />';
		
	}

	function widget($args){
    	extract($args);
		$options = get_option('widget_twFeed');
		echo '<h2 class="widgettitle">'.$options['title'].'</h2>';
		$widget_feed = new twFeed();
		$widget_feed->get_twFeed($options['user'],$options['posts']);
  	}
  	  	
	function register(){
		register_sidebar_widget('twFeed', array('twFeed', 'widget'));
		register_widget_control('twFeed', array('twFeed', 'control'));
	}
}

?>
