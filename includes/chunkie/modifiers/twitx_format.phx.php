<?php
/**
 * twitx_format
 *
 * PHx modifier for formatting and linking twitter feed statuses.
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
 * MODX Evolution Port of TwitterXFormat
 * @author Thomas Jakobi <thomas.jakobi@partout.info>
 * @version 0.7
 *
 * TwitterXFormat author: Stewart Orr @ Qodo Ltd <stewart@qodo.co.uk>
 */
$search = array('! @([\w_]+)!', '! #([A-Za-z0-9-_]+)!');
$replace = array(' <a href="http://twitter.com/$1" target="_blank" rel="nofollow">@$1</a>', ' <a href="http://search.twitter.com/search?q=%23$1" target="_blank" rel="nofollow">#$1</a>');
return preg_replace($search, $replace, $output);
?>
