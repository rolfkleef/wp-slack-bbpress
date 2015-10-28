<?php
/**
 * Plugin Name: WP Slack bbPress
 * Plugin URI:  https://github.com/rolfkleef/wp-slack-bbpress
 * Description: Send notifications to Slack channels for events in bbPress.
 * Version:     0.5
 * Author:      Rolf Kleef
 * Author URI:  https://drostan.org
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-slack-bbpress
 */

function wp_slack_bbpress( $events ) {
	$events['wp_slack_bbp_new_topic'] = array(
		'action' => 'bbp_new_topic',
		'description' => __( 'When a new topic is added in bbPress', 'wp-slack-bbpress' ),
		'message'     => function( $topic_id,  $forum_id,  $anonymous_data,  $topic_author ) {
			return array(array(
				'fallback' => sprintf(
					__( '<%1$s|New topic "%2$s"> in forum <%3$s|%4$s>', 'wp-slack-bbpress' ),
					bbp_get_topic_permalink( $topic_id ),
					bbp_get_topic_title( $topic_id ),
					bbp_get_forum_permalink( $forum_id ),
					bbp_get_forum_title( $forum_id )
				),

				'pretext'=> sprintf(
					__( 'New topic in forum <%1$s|%2$s>', 'wp-slack-bbpress' ),
					bbp_get_forum_permalink( $forum_id ),
					bbp_get_forum_title( $forum_id )
				),

				'author_name' => bbp_get_topic_author_display_name( $topic_id ),
				'author_link' => bbp_get_topic_author_link( $topic_id ),
				'author_icon' => get_avatar_url( $topic_author, array( 'size' => 16 ) ),

				'title' => sprintf(
					'%1$s',
					bbp_get_topic_title( $topic_id )
				),
				'title_link' => bbp_get_topic_permalink( $topic_id ),

				'text' => html_entity_decode( bbp_get_topic_excerpt( $topic_id, 150 ) ),
			));
		}
	);

	$events['wp_slack_bbp_new_reply'] = array(
		'action' => 'bbp_new_reply',
		'description' => __( 'When a new reply is added in bbPress', 'wp-slack-bbpress' ),
		'message'     => function( $reply_id,  $topic_id,  $forum_id,  $anonymous_data,  $reply_author,  $bool,  $reply_to ) {
			return array(array(
				'fallback' => sprintf(
					__( '<%1$s|New reply> in forum <%2$s|%3$s> on topic <%4$s|%5$s>', 'wp-slack-bbpress' ),
					bbp_get_reply_url( $reply_id ),
					bbp_get_forum_permalink( $forum_id ),
					bbp_get_forum_title( $forum_id ),
					bbp_get_topic_permalink( $topic_id ),
					bbp_get_topic_title( $topic_id )
				),

				'pretext'=> sprintf(
				  __( 'New reply in forum <%1$s|%2$s> on topic <%3$s|%4$s>', 'wp-slack-bbpress' ),
					bbp_get_forum_permalink( $forum_id ),
					bbp_get_forum_title( $forum_id ),
					bbp_get_topic_permalink( $topic_id ),
					bbp_get_topic_title( $topic_id )
				),

				'author_name' => bbp_get_reply_author_display_name( $reply_id ),
				'author_link' => bbp_get_reply_author_link( $reply_id ),
				'author_icon' => get_avatar_url( $reply_author, array( 'size' => 16 ) ),

				'title' => sprintf(
					__( 'New reply to "%1$s"', 'wp-slack-bbpress' ),
					bbp_get_topic_title( $topic_id )
				),
				'title_link' => bbp_get_reply_url( $reply_id ),

				'text' => html_entity_decode( bbp_get_reply_excerpt( $reply_id, 150 ) ),
			));
		}
	);

	return $events;
}

add_filter( 'slack_get_events', 'wp_slack_bbpress' );
