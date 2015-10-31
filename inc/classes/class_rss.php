<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author niczem
 */
class rss {
    //put your code here
    function getRssfeed($rssfeed, $cssclass="", $encode="auto", $anzahl=10, $mode=0) {
         $data = @file($rssfeed);
         $data = implode ("", $data);
             preg_match_all("/<item.*>(.+)<\/item>/Uism", $data, $items);

            if($encode == "auto")
                                    {
                                        preg_match("/<?xml.*encoding=\"(.+)\".*?>/Uism", $data, $encodingarray);
                                        $encoding = $encodingarray[1];
                                    }
            else
                {
                $encoding = $encode;

                }


           $output .= "<div class=\"rssfeed_".$cssclass."\">\n";
                    // Titel und Link zum Channel 
                    if($mode == 1 || $mode == 3)
                    {
                            $data = preg_replace("/<item>(.+)<\/item>/Uism", '', $data);
                            preg_match("/<title>(.+)<\/title>/Uism", $data, $channeltitle);
                            preg_match("/<link>(.+)<\/link>/Uism", $data, $channellink);
                            preg_match("/<url>(.+)<\/url>/Uism", $data, $channelimage);

                            $channeltitle = preg_replace('/<!\[CDATA\[(.+)\]\]>/Uism', '$1', $channeltitle);
                            $channellink = preg_replace('/<!\[CDATA\[(.+)\]\]>/Uism', '$1', $channellink);
                            $channellink = preg_replace('/<!\[CDATA\[(.+)\]\]>/Uism', '$1', $channelimage);

                            $output .= "<center class=\"grayBar\"><img src=\"./gfx/icons/rss.png\" style=\"float: left;\"><a href=\"".$channellink[1]."\" target=\"blank\" title=\"";
                            $output .= "<image src=\"".$channelimage[1]->src."\" title=\"";
                            if($encode != "no")
                            {$output .= htmlentities($channeltitle[1],ENT_QUOTES,$encoding);}
                            else
                            {$output .= $channeltitle[1];}
                            $output .= "\">";
                            if($encode != "no")
                            {$output .= htmlentities($channeltitle[1],ENT_QUOTES,$encoding);}
                            else
                            {$output .= $channeltitle[1];}
                            $output .= "</a></center>\n";
                    }     

                    // Titel, Link und Beschreibung der Items
                    foreach ($items[1] as $item) {
                            preg_match("/<title>(.+)<\/title>/Uism", $item, $title);
                            preg_match("/<link>(.+)<\/link>/Uism", $item, $link);
                            preg_match("/<description>(.*)<\/description>/Uism", $item, $description);

                            $title = preg_replace('/<!\[CDATA\[(.+)\]\]>/Uism', '$1', $title);
                            $description = preg_replace('/<!\[CDATA\[(.+)\]\]>/Uism', '$1', $description);
                            $link = preg_replace('/<!\[CDATA\[(.+)\]\]>/Uism', '$1', $link);

                            $output .= "<div class=\"rssTitle\">\n";
                            $output .= "<a href=\"#\" onclick=\"openURL('".$link[1]."', '$channeltitle'); return false\" target=\"blank\" title=\"";
                            if($encode != "no")
                            {$output .= htmlentities($title[1],ENT_QUOTES,$encoding);}
                            else
                            {$output .= $title[1];}
                            $output .= "\">";
                            if($encode != "no")
                            {$output .= htmlentities($title[1],ENT_QUOTES,$encoding)."</a>\n";}
                            else
                            {$output .= $title[1]."</a>\n";}
                            $output .= "</div>\n";
                            if($mode == 2 || $mode == 3 && ($description[1]!="" && $description[1]!=" "))
                            {
                                    $output .= "<div class=\"rssDescription\">\n";
                                    if($encode != "no")
                                    {$output .= htmlentities($description[1],ENT_QUOTES,$encoding)."\n";}
                                    else
                                    {$output .= $description[1];}
                                    $output .= "</div><hr>\n";
                            }
                            if ($anzahl-- <= 1) break;
                    }
                    $output .= "</div>\n\n";
                    return $output;

    }

    function showRssFeed($url){
       $feed_url = "$url";

       // INITIATE CURL. 
       $curl = curl_init();

       // CURL SETTINGS. 
       curl_setopt($curl, CURLOPT_URL,"$feed_url"); 
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
       curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0); 

       // GRAB THE XML FILE. 
       $xmlTwitter = curl_exec($curl); 
       curl_close($curl);

       // SET UP XML OBJECT.
       // Use either one of these, depending on revision of PHP.
       // Comment-out the line you are not using.
       //$xml = new SimpleXMLElement($xmlTwitter);
       $xml = simplexml_load_string($xmlTwitter); 

       // How many items to display 
       $count = 10; 

       // How many characters from each item 
       // 0 (zero) will show them all. 
       $char = 0; 


       //show channel image
       echo '<img src="'.($xml->channel->image->url).'" class="newsTitleImage">';
       foreach ($xml->channel->item as $item) { 
       if($char == 0){ 
       $newstring = $item->description; 
       } 
       else{ 
       $newstring = substr($item->description, 0, $char); 
       } 
       if($count > 0){
       //in case they have non-closed italics or bold, etc ... 
       echo"</i></b></u></a>\n"; 
       echo" 
       <div style='font-family:arial; font-size: 12pt;' class='newsArticleBox'>
       <h3 style='line-height: 30px;'>{$item->title}</h3>
       $newstring <br><br><a href='#' onclick=\"openURL('{$item->guid}', '{".escape::sql($item->title)."}'); return false\" target='_blank' class='btn btn-info'>read all</a>
       <br /><br /> 
       </div> 
       ";  
       } 
       $count--; 
       } 
    }
}