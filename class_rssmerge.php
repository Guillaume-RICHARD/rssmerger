<?php
/**
 * This class has the ability to merge different RSS feeds and sort them after the date the feed items were posted.
 * @author Guillaume RICHARD <g.jf.richard@gmail.com>
 */
class RSSMerger {
    private $feeds = array();

    /**
     * Constructs a RSSmerger object
     */
    function __construct() {

    }

    /**
     * Populates the feeds array from the given url which is a rss feed
     * @param $url
     */
    function add($url) {
        $xml = new SimpleXMLElement($url, NULL, true);

        foreach($xml->channel->item as $item) {
            $item->sitetitle = $xml->channel->title;
            $item->sitelink = $xml->channel->link;
             
            preg_match("/^[A-Za-z]{3}, ([0-9]{2}) ([A-Za-z]{3}) ([0-9]{4}) ([0-9]{2}):([0-9]{2}):([0-9]{2}) ([\+|\-]?[0-9]{4})$/", $item->pubDate, $match);
            $item->time = mktime($match[4]+($match[6]/100),$match[5],$match[6],date("m",strtotime($match[2])),$match[1],$match[3]);

            $this->feeds[] = $item;
        }
    }
    /**
     * Comparing function for sorting the feeds
     * @param $value1
     * @param $value2
     */
    function feeds_cmp($value1,$value2) {
        if(intval($value1->time) == intval($value2->time))
            return 0;

        return (intval($value1->time) < intval($value2->time)) ? +1 : -1;
    }

    /**
     * Sorts the feeds array using the Compare function feeds_cmp
     */
    function sort() {
        usort($this->feeds,Array("RssMerger","feeds_cmp"));
    }

    /**
     * This function return the feed items.
     * @param $limit how many feed items that should be returned
     * @return the feeds array
     */
    function getFeeds($limit) {
        return array_slice($this->feeds,0,$limit);
    }
}
?>
