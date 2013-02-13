<?
/**
 * TwitX
 *
 * This MODX snippet loads Twitter feeds using the Twitter 1.1 REST API. 
 * You will need to create a Twitter app and get the keys and tokens: 
 * https://dev.twitter.com/apps/new
 *
 * This Snippet uses evoChunkie
 * This Snippet uses TwitterOAuth: https://github.com/abraham/twitteroauth
 * This Snippet uses Cache Class: http://www.axel-hahn.de/php_contentcache.php
 *
 * TwitX is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * TwitX is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * 
 * MODX Evolution Port of TwitterX
 * @author Thomas Jakobi <thomas.jakobi@partout.info> 
 * @version 0.9
 *
 * description: <strong>0.9</strong> Load Twitter feeds using the Twitter 1.1 REST API
 * 
 * TwitterX author: Stewart Orr @ Qodo Ltd <stewart@qodo.co.uk>
 */
// Twitter API keys and secrets
$twitter_consumer_key = isset($twitter_consumer_key) ? $twitter_consumer_key : FALSE;
$twitter_consumer_secret = isset($twitter_consumer_secret) ? $twitter_consumer_secret : FALSE;
$twitter_access_token = isset($twitter_access_token) ? $twitter_access_token : FALSE;
$twitter_access_token_secret = isset($twitter_access_token_secret) ? $twitter_access_token_secret : FALSE;

// Other options
$limit = isset($limit) ? $limit : 5;
$twitTpl = isset($twitTpl) ? $twitTpl : '@FILE:assets/snippets/twitx/templates/twitTpl.html';
$timeline = isset($timeline) ? $timeline : 'user_timeline';
$cache = isset($cache) ? $cache : 7200;
$screen_name = isset($screen_name) ? $screen_name : '';
$include_rts = (isset($include_rts) && (!$include_rts)) ? 0 : 1;
$outputSeparator = isset($outputSeparator) ? $outputSeparator : "\r\n";
$toPlaceholder = isset($toPlaceholder) ? $toPlaceholder : '';

if (!class_exists('evoChunkie')) {
	require MODX_BASE_PATH . 'assets/snippets/twitx/includes/chunkie/chunkie.class.inc.php';
}
if (!class_exists('evoCache')) {
	require MODX_BASE_PATH . 'assets/snippets/twitx/includes/cache/cache.class.php';
}
if (!class_exists('TwitterOAuth')) {
	require MODX_BASE_PATH . 'assets/snippets/twitx/includes/twitteroauth/twitteroauth.php';
}

// HTML output 
$output = array();
// If they haven't specified the required Twitter keys, we cannot continue...
if (!$twitter_consumer_key || !$twitter_consumer_secret || !$twitter_access_token || !$twitter_access_token_secret) {
	$output[] = '<strong>TwitX Error:</strong> Could not load TwitX as required values were not passed.';
} else {
	// Test for required function(s)
	if (!function_exists('curl_init')) {
		$output[] = "<strong>TwitX Error:</strong> cURL functions do not exist, cannot continue.";
	} else {
		// Try loading the data from cache first
		$myCache = new evoCache('TwitX', $screen_name . '_' . $timeline);

		if ($myCache->isExpired()) {
			// Load the TwitterOAuth lib required if not exists
			// Create new twitteroauth
			$twitteroauth = new TwitterOAuth($twitter_consumer_key, $twitter_consumer_secret, $twitter_access_token, $twitter_access_token_secret);

			// We want to use JSON format
			$twitteroauth->format = 'json';
			$twitteroauth->decode_json = FALSE;

			// Request statuses with optinal parameters
			$options = array(
				'count' => $limit + 1,
				'include_rts' => $include_rts
			);
			// If we have a screen_name, pass this to Twitter API
			if ($screen_name != '') {
				$options['screen_name'] = $screen_name;
			}
			// If we are viewing favourites or regular statuses
			if ($timeline != 'favorites') {
				$timeline = 'statuses/' . $timeline;
			}
			$json = $twitteroauth->get($timeline, $options);

			// No errors? Save to Cache
			if (!isset($json->error)) {
				$myCache->write($json, $cache);
			}
		} else {
			// read cached data
			$json = $myCache->read();
		}

		// Decode this now that we have used it above in the cache
		$json = json_decode($json);

		// If there any errors from Twitter, output them...
		if (isset($json->error)) {
			$output[] = "<strong>TwitX Error:</strong> Could not load TwitX. Twitter responded with the error '" . $json->error . "'.";
		} else {

			$parser = new evoChunkie($twitTpl);
			// For each result, output it
			foreach ($json as $j) {

				// Get placerholder values
				$placeholders = array(
					'created_at' => $j->created_at,
					'source' => $j->source,
					'id' => $j->id,
					'id_str' => $j->id_str,
					'text' => $j->text,
					'name' => $j->user->name,
					'screen_name' => $j->user->screen_name,
					'profile_image_url' => $j->user->profile_image_url,
					'location' => $j->user->location,
					'url' => $j->user->url,
					'description' => $j->user->description,
				);
				// If this is a retweet, create placeholders for this too
				if (isset($j->retweeted_status)) {
					$placeholders = array_merge($placeholders, array(
						'retweet_count' => $j->retweeted_status->retweet_count,
						'retweet_created_at' => $j->retweeted_status->created_at,
						'retweet_source' => $j->retweeted_status->source,
						'retweet_id' => $j->retweeted_status->id,
						'retweet_id_str' => $j->retweeted_status->id_str,
						'retweet_text' => $j->retweeted_status->text,
						'retweet_name' => $j->retweeted_status->user->name,
						'retweet_screen_name' => $j->retweeted_status->user->screen_name,
						'retweet_profile_image_url' => $j->retweeted_status->user->profile_image_url,
						'retweet_location' => $j->retweeted_status->user->location,
						'retweet_url' => $j->retweeted_status->user->url,
						'retweet_description' => $j->retweeted_status->user->description,
							)
					);
				}
				$parser->CreateVars($placeholders);
				// Parse chunk passing values
				$output[] = $parser->Render();
			}

			// Added option to output to placeholder
			if ($toPlaceholder != '') {
				$modx->setPlaceholder($toPlaceholder, implode($outputSeparator, $output));
				$output = array();
				$outputSeparator = '';
			}
		}
	}
}
return implode($outputSeparator, $output);
?>