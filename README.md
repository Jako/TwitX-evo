TwitX
================================================================================

Load and display Twitter feeds and post Tweets using the Twitter 1.1 REST API

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
timeline | name of the twitter timeline* | user_timeline
decodeUrls | decode shortened t.co urls | 1;
cache | seconds the twitter feed ist cached | 7200
screen_name | screen name of the user the twitter feed is loaded from | -
include_rts | include retweets | 1
outputSeparator | separator between two twitTpl elements | `newline`
toPlaceholder | a placeholder name the snippet output is assigned to. surpesses normal snippet output | -

* Possible timelines: favorites, mentions_timeline, user_timeline, home_timeline, retweets_of_me

Placeholder
--------------------------------------------------------------------------------
Look in the Twitter 1.1 REST API for possible placeholder in the TwitX template.

Each member of the response object could be referenced by its name. Members of a member could be referenced by name of the parent member and the name of the member divided by `.`.

Example response object:

```
[
  {
    "created_at": "Wed Aug 29 17:12:58 +0000 2012",
    "entities": {
      "urls": [
        {
          "expanded_url": "https://dev.twitter.com/blog/twitter-certified-products"
        }
      ]
    }
  }
]
```

Possible placeholder showing a value are: `[+created_at+]` `[+entities.urls.expanded_url+]`

These placeholder could be modified by PHx modifier.

Notes:
--------------------------------------------------------------------------------
1. Uses Cache Class: http://www.axel-hahn.de/php_contentcache.php
2. Uses TwitterOAuth Class: https://github.com/abraham/twitteroauth
3. TwitX is an Evolution Port of TwitterX: http://modx.com/extras/package/twitterx