<?php
/*
Plugin Name: twFeed
Plugin URI: http://www.paulcormack.net/projects#twFeed
Description: Integrates a Twitter RSS feed into your blog. Comes widget ready.
Version: 1.1
License: GPLV2
Author: Paul Cormack
Author URI: http://www.paulcormack.net/
*/

class twFeed extends WP_Widget {

	function twFeed() {

		$widget_ops = array( 'classname' => 'widget_twFeed', 'description' => __( "A twitter RSS parser." ) );
		$this->WP_Widget('twFeed', __('twFeed'), $widget_ops);
	
	}
		
	function get_twFeed($u_opts){
		$twFeed_before = '<ul class="twFeed">';
		$twFeed_after = '</ul>';
		$url_regex = '`\b(https?|ftp)://[-A-Za-z0-9+&@#/%?=~_()|!:,.;]*[-A-Za-z0-9+&@#/%=~_()|]\b`';
		$mention_regex = '`(^|[\n ])@+([A-Za-z0-9-_]+)`';
		
		if (empty($u_opts)) $u_opts = $u_defaults;
    	if (!isset($u_opts['user'])) $u_opts['user'] = $u_defaults['user'];
    	if (!isset($u_opts['title'])) $u_opts['title'] = $u_defaults['title'];
    	if (!isset($u_opts['post_count'])) $u_opts['post_count'] = $u_defaults['post_count'];
    	if (!is_numeric($u_opts['post_count']) && $u_opts['post_count'] < 1 && $u_opts['post_count'] >= 20 ) $u_opts['post_count'] = $u_defaults['post_count'];
    	if (!isset($u_opts['show_date'])) $u_opts['show_date'] = $u_defaults['show_date'];

		$twFeed_url = "http://twitter.com/statuses/user_timeline/" . 
			$u_opts['user'] . ".rss";

		if (!function_exists('MagpieRSS')) {
			include_once (ABSPATH . WPINC . '/rss.php');
		}

		$twFeed_full_contents = @fetch_rss($twFeed_url);

		if ($twFeed_full_contents) {
			$twFeed_contents = array_slice($twFeed_full_contents->items, 0, $u_opts['post_count']);

			echo $twFeed_before;
			foreach( $twFeed_contents as $tweet ) {
				$twFeed_desc = $tweet['description'];
				$twFeed_status = $tweet['link'];
				$twFeed_fdate = explode(' ',$tweet['pubdate']);
				$twFeed_hrmin = substr($twFeed_fdate[4], 0, -3);
				$twFeed_date = $twFeed_fdate[1] . ' ' . $twFeed_fdate[2] . ' ' . $twFeed_hrmin;
				$tweeter = explode (' ', $twFeed_desc, 2);
				$twFeed_tweet = $tweeter[1];
				$twFeed_tweet = htmlspecialchars(stripslashes($twFeed_tweet));
				$twFeed_tweet = preg_replace($url_regex, 
					'<a href="\0" title="\0" alt="\0" target="_blank">\0</a>', 
					$twFeed_tweet );
				$twFeed_tweet = preg_replace($mention_regex, 
					' @<a class="twFeed_mention" href="http://www.twitter.com/\\2" title="\\2" ' . 
					'alt="\\2" target="_blank">\\2</a>', $twFeed_tweet);

				if ($u_opts['show_date']) {
					$twFeed_tweet = $twFeed_tweet . ' <a class="twFeed_date" href="' . 
						$twFeed_status . '" target="_blank">' . $twFeed_date . '</a>';
           			echo '<li>' . $twFeed_tweet . '</li>';
            	}
            	else {
           			echo '<li>'.$twFeed_tweet.'</li>';
            	}
			}
			echo $twFeed_after;
		}

		else {
			echo "RSS problems, check twitter username...\n";
			echo $twFeed_url;
		}
	}

	function widget($args, $instance) {
		
		extract($args);
		echo $before_widget;
		if(!empty($instance['title'])) echo $before_title . $instance['title'] . $after_title; 
		$widget_feed = new twFeed();
		$widget_feed->get_twFeed(array(
			'user'=>$instance['user'],
			'title'=>$instance['title'], 
			'post_count'=>$instance['post_count'],
			'show_date'=>$instance['show_date'] ));
		echo $after_widget;
	
	}

	function update($new_instance, $old_instance) {

		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['user'] = strip_tags( $new_instance['user'] );
		$instance['post_count'] = strip_tags( $new_instance['post_count'] );
		$instance['show_date'] = strip_tags( $new_instance['show_date'] );
		return $new_instance;
	
	}

	function form($instance) {
		echo '<div id="twFeed-admin-panel">';
		echo '<p><label for="' . $this->get_field_id("title") .'">Widget Title:</label>';
		echo '<input type="text" class="widefat" name="' . $this->get_field_name("title") . '" ';
		echo 'id="' . $this->get_field_id("title") . '" value="' . $instance["title"] . '" /></p>';
		echo '<p><label for="' . $this->get_field_id("user") .'">Twitter User:</label>';
		echo '<input type="text" class="widefat" name="' . $this->get_field_name("user") . '" ';
		echo 'id="' . $this->get_field_id("user") . '" value="' . $instance["user"] . '" /></p>';
		echo '<p><label for="' . $this->get_field_id("post_count") . '">How many tweets?</label><br />';
		echo '<select id="' . $this->get_field_id('post_count') . '" name="' . 
			$this->get_field_name('post_count') . '" class="widefat">';
		for ($i=1; $i<=20; $i++) {
			echo '<option value="' . $i . '"'; 
				if ( $i == $instance['post_count'] ) echo ' selected="selected"';
			echo '">' . $i . '</option>';
		}
		echo '</select></p>';
		echo '<p><label for="' . $this->get_field_id('show_date') . 
			'">Show timestamps?</label><br />';
		echo '<select id="' . $this->get_field_id('show_date') . '" name="' . 
			$this->get_field_name('show_date') . '" class="widefat">';
		echo '<option value="0" '; 
			if ( $instance['show_date'] === 0 ) echo 'selected="selected"';
		echo '">Off</option><option value="1" ';
			if ( $instance['show_date'] == 1 ) echo 'selected="selected"';
		echo '">On</option></select></p>';
		echo '</div>';
	}
}
add_action('widgets_init', create_function('', 'return register_widget("twFeed");'));

?>
