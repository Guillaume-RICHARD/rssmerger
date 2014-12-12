<?php 
require_once "class_rssmerge.php";
require_once "class_rssfeed.php";

$rss = new RSSMerger();
$rss->add("http://jf-blog.fr/feed");
$rss->add("http://365idees.jf-blog.fr/feed");
$rss->add("http://www.pressmyweb.com/author/guillaume/feed/");
$rss->sort();

$xml = new RSSFeed("RSS merger","http://guillaume-richard.fr/rss/","a simple rss merge script","http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$xml->setLanguage("en");

$feeds = $rss->getFeeds(20);

foreach($feeds as $f) {
    $xml->addItem($f->title,$f->link,$f->description,$f->author,$f->guid,$f->time);
}
$xml->displayXML();
?>
