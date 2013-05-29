TwitX
================================================================================

Load and display Twitter feeds in MODX Evolution using the Twitter 1.1 REST API.

Features:
--------------------------------------------------------------------------------
This MODX Evolution snippet loads Twitter feeds using the Twitter 1.1 REST API. You will need to create a Twitter app and get the keys, secrets and token on https://dev.twitter.com/apps/new

Installation:
--------------------------------------------------------------------------------
1. Upload all files into the new folder *assets/snippets/twitx*
2. Create a new snippet called TwitX with the following snippet code
    `<?php return include (MODX_BASE_PATH . 'assets/snippets/twitx/twitx.snippet.php'); ?>`
3. If you are using the PHx plugin (or more than one Snippet/Plugin with evoChunkie class) you should copy the PHx modifier from the folder `phx_modifier` to `assets/plugins/phx/modifiers`
4. Create a Twitter app on https://dev.twitter.com/apps/new and note the Consumer Key and the Consumer Secret. On that page you have to create an access token and note the Access Token and the Access Token Secret. These values have to be used later in the snippet call.

Usage
--------------------------------------------------------------------------------

Basic snippet call:

```
[!TwitX? 
    &twitter_consumer_key=`aaaa`
    &twitter_consumer_secret=`bbbb` 
    &twitter_access_token=`cccc` 
    &twitter_access_token_secret=`dddd`
!]
```
The snippet could use the following properties:

Property | Description | Default
---- | ----------- | -------
twitter_consumer_key | **required:** your twitter consumer token  | -
twitter_consumer_secret | **required:** your twitter consumer secret | -
twitter_access_token | **required:** your twitter access token | -
twitter_access_token_secret | **required:** your twitter access token secret | -
limit | limit the number of statuses to display | 5
twitTpl | template chunk for one twitter status | `twitTpl.html` in folder `templates`
timeline | name of the twitter timeline | user_timeline
cache | seconds the twitter feed ist cached | 7200
screen_name | screen name of the user the twitter feed is loaded from | -
include_rts | include retweets | 1
outputSeparator | separator between two twitTpl elements | `newline`
toPlaceholder | a placeholder name the snippet output is assigned to. surpesses normal snippet output | -

Placeholder
--------------------------------------------------------------------------------
The following placeholder could be used in the template chunk:

Placeholder | Value
----------- | ------------------------------------------------------------------
created_at | create date of the twitter status
source | source of the twitter status (application like web, iOS etc)
id | id of the twitter status
text | text of the twitter status
name | name of the twitter status creator
screen_name | username of the twitter status creator
profile_image_url | avatar image url of the twitter status creator
location | location of the twitter status creator
url | url of the twitter status creator
description | user profile of the twitter status creator

For retweets the following placeholder could be used in the template chunk:

Placeholder | Value
----------- | ------------------------------------------------------------------
retweet_created_at | create date of the retweeted twitter status
retweet_source | source of the retweeted twitter status (application like web, iOS etc)
retweet_id | id of the retweeted twitter status
retweet_id_str | id of the retweeted twitter status (twitter.com/user/statuses/id_str)
retweet_text | text of the retweeted twitter status
retweet_name | name of the retweeted twitter status creator
retweet_screen_name | username of the retweeted twitter status creator
retweet_profile_image_url | avatar image url of the retweeted twitter status creator
retweet_location | location of the retweeted twitter status creator
retweet_url | url of the retweeted twitter status creator
retweet_description | user profile of the retweeted twitter status creator

Notes:
--------------------------------------------------------------------------------
1. Uses Cache Class: http://www.axel-hahn.de/php_contentcache.php
2. Uses TwitterOAuth Class: https://github.com/abraham/twitteroauth
3. TwitX is an Evolution Port of TwitterX: http://modx.com/extras/package/twitterx
