###############################################################################

cache.class.php

###############################################################################

AXELS CONTENT CACHE CLASS
V2.0

http://www.axel-hahn.de/php_contentcache.php
License: GNU/GPL v3

--------------------------------------------------------------------------------

2009-07-20  1.0  cache class on www.axel-hahn.de to store strings
2012-02-05  2.0  cache serialzable types; more methods, i.e.:
                   - comparison of timestimp with a sourcefile (see example below)
                   - cleanup unused cachefiles

###############################################################################

--- typical usage:

--------------------------------------------------------------------------------

example using expiration (ttl value):

    $sContent='';  
    $iTtl=60*5; // 5 min 
      
    require_once("/php/cache.class.php");  
    $myCache=new Cache("my-app","task-id");  
      
    if($myCache->isExpired()) {  
        // cache does not exist or is expired
        $sContent=...  
      
        // save cache
        $myCache->write($sContent, $iTtl);  
      
    } else {  
        // read cached data
        $sContent=$myCache->read();  
    }  
      
    // output
    echo $sContent;  
	
--------------------------------------------------------------------------------

example compare age of cache with age of a sourcefile

	require_once("/php/cache.class.php");  
	$sCsvFile="my_source_file.csv"  
	  
	$myCache=new Cache("my-app","task-id");  
	$sContent=$myCache->read(); // read cached data
	  
	// comparison of last modified time (mtime)
	if (!$myCache->isNewerThanFile($sCsvFile)) {  
	  
		// update content 
		$sContent=...  
	  
		// ... and save cache
		$myCache->write($sContent);  
	};  
	  
	// output
	echo $sContent;

--------------------------------------------------------------------------------

cleanup cache directory 

    require_once("/php/cache.class.php");  
    $o=new Cache("my-app");  
    $o->cleanup(60*60*24*1); // alle Cachefiles des Moduls "mein-modul" älter 1 Tag löschen  

	or cleanup cachefiles of all modules
    $o=new Cache(); $o->cleanup(60*60*24*1);


###############################################################################
